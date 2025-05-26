package com.example.project;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class history extends AppCompatActivity {
    private RecyclerView recyclerView;
    private AttendanceAdapter adapter;
    private List<Attendance> attendanceList = new ArrayList<>();
    private FirebaseFirestore db;
    private TextView textViewAttendanceSummary; // TextView untuk menampilkan hasil kehadiran

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_history);

        recyclerView = findViewById(R.id.recyclerViewAttendance);
        textViewAttendanceSummary = findViewById(R.id.textViewAttendanceSummary); // Menghubungkan TextView

        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        adapter = new AttendanceAdapter(attendanceList);
        recyclerView.setAdapter(adapter);

        db = FirebaseFirestore.getInstance();

        SharedPreferences sharedPreferences = getSharedPreferences("UserSession", MODE_PRIVATE);
        String userId = sharedPreferences.getString("userId", null);

        if (userId != null) {
            loadAttendanceHistory(userId);
        } else {
            Toast.makeText(this, "User ID tidak ditemukan", Toast.LENGTH_SHORT).show();
        }
    }

    private void loadAttendanceHistory(String userId) {
        db.collection("user_attendance").document(userId)
                .collection("attendance")
                .get()
                .addOnSuccessListener(queryDocumentSnapshots -> {
                    Map<String, int[]> monthlyAttendanceMap = new HashMap<>();

                    for (DocumentSnapshot document : queryDocumentSnapshots) {
                        Attendance attendance = document.toObject(Attendance.class);
                        if (attendance != null) {
                            String status = attendance.getStatus();
                            String timestamp = attendance.getTimestamp();

                            // Parse timestamp untuk mendapatkan bulan dan tahun
                            String monthYear = getMonthYear(timestamp);

                            // Inisialisasi count jika belum ada untuk bulan ini
                            if (!monthlyAttendanceMap.containsKey(monthYear)) {
                                monthlyAttendanceMap.put(monthYear, new int[]{0, 0}); // [Count Hadir, Count Absen]
                            }

                            // Update count berdasarkan status kehadiran
                            if ("Hadir".equalsIgnoreCase(status)) {
                                monthlyAttendanceMap.get(monthYear)[0]++;
                            } else if ("Tidak Hadir".equalsIgnoreCase(status)) {
                                monthlyAttendanceMap.get(monthYear)[1]++;
                            }

                            attendanceList.add(attendance); // Menambahkan data absensi ke dalam list untuk RecyclerView
                        }
                    }

                    // Mengurutkan attendanceList berdasarkan timestamp dalam urutan menurun (terbaru ke lama)
                    attendanceList.sort((a1, a2) -> {
                        try {
                            SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss"); // Menyesuaikan format timestamp
                            return format.parse(a2.getTimestamp()).compareTo(format.parse(a1.getTimestamp())); // Membandingkan dalam urutan terbalik
                        } catch (ParseException e) {
                            e.printStackTrace();
                            return 0;
                        }
                    });

                    // Menampilkan ringkasan bulanan absensi
                    displayMonthlySummary(monthlyAttendanceMap);

                    // Memberitahukan adapter untuk menyegarkan data
                    adapter.notifyDataSetChanged();
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(this, "Gagal memuat histori absensi", Toast.LENGTH_SHORT).show();
                    Log.e("AttendanceHistory", "Error loading attendance history", e);
                });
    }

    private String getMonthYear(String timestamp) {
        try {
            // Convert timestamp ke Date
            SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss"); // Menyesuaikan format timestamp
            java.util.Date date = format.parse(timestamp);

            // Buat SimpleDateFormat untuk mendapatkan Bulan dan Tahun
            SimpleDateFormat monthYearFormat = new SimpleDateFormat("yyyy-MM");
            return monthYearFormat.format(date);
        } catch (ParseException e) {
            e.printStackTrace();
            return "";
        }
    }

    private void displayMonthlySummary(Map<String, int[]> monthlyAttendanceMap) {
        StringBuilder summary = new StringBuilder();

        for (Map.Entry<String, int[]> entry : monthlyAttendanceMap.entrySet()) {
            String monthYear = entry.getKey();
            int[] counts = entry.getValue();
            int hadirCount = counts[0];
            int absenCount = counts[1];

            // Menambahkan hasil untuk setiap bulan
            summary.append("Bulan: ").append(monthYear)
                    .append(" - Hadir: ").append(hadirCount)
                    .append(", Absen: ").append(absenCount)
                    .append("\n");
        }

        // Update TextView dengan ringkasan
        textViewAttendanceSummary.setText(summary.toString());
    }

    @SuppressLint("MissingSuperCall")
    @Override
    public void onBackPressed() {
        // Mengarahkan ke Home screen atau mengakhiri activity sesuai kebutuhan
        Intent intent = new Intent(history.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
        startActivity(intent);
        finish();
    }
}
