package com.example.project;

import android.Manifest;
import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.location.Location;
import android.os.Build;
import android.os.Bundle;
import android.provider.MediaStore;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.firebase.firestore.FirebaseFirestore;

import java.io.ByteArrayOutputStream;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class absen extends AppCompatActivity {

    private static final int REQUEST_LOCATION_PERMISSION = 1;
    private static final int REQUEST_IMAGE_CAPTURE = 1;
    private static final int REQUEST_CAMERA_PERMISSION = 100;
    private String base64Image;

    private FirebaseFirestore db;
    private FusedLocationProviderClient fusedLocationClient;

    private TextView textViewUserId, textView;
    private EditText locationInput;
    private EditText editTextReason;

    private ImageView imageView;
    private Button buttonTakePhoto, btnSubmitAttendance;
    private RadioGroup radioGroupPresence;
    private RadioButton radioPresent, radioAbsent;

    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_absen);

        db = FirebaseFirestore.getInstance();
        fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);

        editTextReason = findViewById(R.id.editTextReason);
        textViewUserId = findViewById(R.id.textViewUserId);
        textView = findViewById(R.id.text1);
        locationInput = findViewById(R.id.location);
        btnSubmitAttendance = findViewById(R.id.kirim);
        radioGroupPresence = findViewById(R.id.radioGroupPresence);
        radioPresent = findViewById(R.id.radioPresent);
        radioAbsent = findViewById(R.id.radioAbsent);

        imageView = findViewById(R.id.imageView);
        buttonTakePhoto = findViewById(R.id.buttonTakePhoto);

        radioGroupPresence.setOnCheckedChangeListener((group, checkedId) -> {
            if (checkedId == R.id.radioAbsent) {
                editTextReason.setVisibility(EditText.VISIBLE);
            } else {
                editTextReason.setVisibility(EditText.GONE);
                editTextReason.setText("");
            }
        });

        buttonTakePhoto.setOnClickListener(v -> {
            if (ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.CAMERA}, REQUEST_CAMERA_PERMISSION);
            } else {
                takePhoto();
            }
        });

        btnSubmitAttendance.setOnClickListener(v -> submitAttendance());
    }

    private void takePhoto() {
        Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
            startActivityForResult(takePictureIntent, REQUEST_IMAGE_CAPTURE);
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == REQUEST_IMAGE_CAPTURE && resultCode == RESULT_OK) {
            Bundle extras = data.getExtras();
            Bitmap imageBitmap = (Bitmap) extras.get("data");
            imageView.setImageBitmap(imageBitmap);
            base64Image = convertToBase64(imageBitmap);
        }
    }




    @SuppressLint("MissingPermission")
    private void submitAttendance() {
        // Get current time
        long currentTimeMillis = System.currentTimeMillis();
        Date currentDate = new Date(currentTimeMillis);
        SimpleDateFormat timeFormat = new SimpleDateFormat("HH:mm", Locale.getDefault());
        String currentTime = timeFormat.format(currentDate);

        // Define the time window for attendance
        String startTime = "01:00"; // Start time
        String endTime = "24:00";   // End time

        // Check if current time is within the allowed window
        if (!isTimeInRange(currentTime, startTime, endTime)) {
            Toast.makeText(this, "Absen hanya dapat dilakukan antara " + startTime + " dan " + endTime, Toast.LENGTH_SHORT).show();
            return;
        }

        // Continue with the attendance submission if within allowed time
        int selectedId = radioGroupPresence.getCheckedRadioButtonId();
        if (selectedId == -1) {
            Toast.makeText(this, "Silakan pilih kehadiran Anda!", Toast.LENGTH_SHORT).show();
            return;
        }

        RadioButton selectedButton = findViewById(selectedId);
        String attendanceStatus = selectedButton.getText().toString();

        if ("Tidak Hadir".equals(attendanceStatus)) {
            String reason = editTextReason.getText().toString().trim();
            if (reason.isEmpty()) {
                Toast.makeText(this, "Silakan masukkan alasan ketidakhadiran!", Toast.LENGTH_SHORT).show();
                return;
            }
        }

        SharedPreferences sharedPreferences = getSharedPreferences("UserSession", MODE_PRIVATE);
        String userId = sharedPreferences.getString("userId", null);
        if (userId == null) {
            Toast.makeText(this, "User ID not found", Toast.LENGTH_SHORT).show();
            return;
        }

        fusedLocationClient.getLastLocation().addOnSuccessListener(location -> {
            if (location != null && location.getLatitude() != 0 && location.getLongitude() != 0) {
                // Check for mock location
                if (isMockLocationEnabled(location)) {
                    Toast.makeText(this, "Lokasi palsu terdeteksi! Absen tidak diperbolehkan.", Toast.LENGTH_SHORT).show();
                    return;
                }

                // Target latitude and longitude
                double targetLat = -7.5666; // Contoh target
                double targetLng = 110.8166;

                // Calculate distance
                double distance = calculateDistance(location.getLatitude(), location.getLongitude(), targetLat, targetLng);

                if (distance > 100000) { // 1000 meter radius
                    Toast.makeText(this, "Anda berada di luar radius absensi!", Toast.LENGTH_SHORT).show();
                    return;
                }

                // Prepare attendance data
                String locationString = String.format(Locale.getDefault(), "%.6f, %.6f", location.getLatitude(), location.getLongitude());
                String timestamp = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).format(new Date());
                String reason = editTextReason.getVisibility() == View.VISIBLE ? editTextReason.getText().toString().trim() : "";

                if (base64Image == null || base64Image.isEmpty()) {
                    Toast.makeText(this, "Foto harus diambil sebelum absen!", Toast.LENGTH_SHORT).show();
                    return;
                }

                Attendance attendance = new Attendance(
                        userId,
                        attendanceStatus,
                        locationString,
                        base64Image,
                        timestamp,
                        reason
                );

                // Disable submit button to prevent multiple clicks
                btnSubmitAttendance.setEnabled(false);

                // Save to Firestore
                db.collection("user_attendance").document(userId)
                        .collection("attendance")
                        .add(attendance)
                        .addOnSuccessListener(aVoid -> {
                            Toast.makeText(this, "Attendance successfully recorded", Toast.LENGTH_SHORT).show();
                            btnSubmitAttendance.setEnabled(true);
                            startActivity(new Intent(this, MainActivity.class));
                        })
                        .addOnFailureListener(e -> {
                            Toast.makeText(this, "Failed to record attendance", Toast.LENGTH_SHORT).show();
                            btnSubmitAttendance.setEnabled(true);
                        });
            } else {
                Toast.makeText(this, "Gagal mendapatkan lokasi yang valid!", Toast.LENGTH_SHORT).show();
            }
        }).addOnFailureListener(e -> {
            Toast.makeText(this, "Gagal mendapatkan lokasi: " + e.getMessage(), Toast.LENGTH_SHORT).show();
            btnSubmitAttendance.setEnabled(true);
        });
    }

    // Method to detect mock location
    private boolean isMockLocationEnabled(Location location) {
        return location.isFromMockProvider();
    }

    // Method to calculate distance between two points
    private double calculateDistance(double lat1, double lon1, double lat2, double lon2) {
        final int R = 6371; // Radius of the earth in km
        double latDistance = Math.toRadians(lat2 - lat1);
        double lonDistance = Math.toRadians(lon2 - lon1);
        double a = Math.sin(latDistance / 2) * Math.sin(latDistance / 2)
                + Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2))
                * Math.sin(lonDistance / 2) * Math.sin(lonDistance / 2);
        double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c * 1000; // Convert to meters
    }











    // Helper method to check time range
    private boolean isTimeInRange(String currentTime, String startTime, String endTime) {
        try {
            SimpleDateFormat sdf = new SimpleDateFormat("HH:mm", Locale.getDefault());
            Date current = sdf.parse(currentTime);
            Date start = sdf.parse(startTime);
            Date end = sdf.parse(endTime);

            return current != null && current.after(start) && current.before(end);
        } catch (Exception e) {
            e.printStackTrace();
            return false;
        }
    }








    // Fungsi untuk menghitung jarak antara dua titik (dalam meter)


    private String convertToBase64(Bitmap bitmap) {
        ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, byteArrayOutputStream);
        byte[] byteArray = byteArrayOutputStream.toByteArray();
        return Base64.encodeToString(byteArray, Base64.DEFAULT);
    }

    @SuppressLint("MissingSuperCall")
    @Override
    public void onBackPressed() {
        // Mengarahkan ke Home screen atau mengakhiri activity sesuai kebutuhan
        Intent intent = new Intent(absen.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
        startActivity(intent);
        finish();
    }
}
