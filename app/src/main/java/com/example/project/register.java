package com.example.project;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.SetOptions;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

public class register extends AppCompatActivity {

    private EditText usernameEditText, passwordEditText, nomerEditText;
    private AutoCompleteTextView autoCompleteTextView;
    private Button registerButton;
    private FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        // Inisialisasi Firebase
        db = FirebaseFirestore.getInstance();

        // Inisialisasi komponen UI
        usernameEditText = findViewById(R.id.eusername);
        passwordEditText = findViewById(R.id.epassword);
        nomerEditText = findViewById(R.id.eAbsen);
        autoCompleteTextView = findViewById(R.id.autoCompleteTextView);
        registerButton = findViewById(R.id.RegisterButton);

        // Data untuk dropdown
        String[] items = {"PPLGA", "PPLGB", "PPLGC"};
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_dropdown_item_1line,
                items
        );

        autoCompleteTextView.setAdapter(adapter);

        // Event untuk dropdown item klik
        autoCompleteTextView.setOnItemClickListener((parent, view, position, id) -> {
            String selectedItem = parent.getItemAtPosition(position).toString();
            Toast.makeText(this, "Kelas dipilih: " + selectedItem, Toast.LENGTH_SHORT).show();
        });

        // Event untuk tombol register
        registerButton.setOnClickListener(view -> registerUser());
    }

    private void registerUser() {
        String username = usernameEditText.getText().toString().trim();
        String password = passwordEditText.getText().toString().trim();
        String kelas = autoCompleteTextView.getText().toString().trim();
        String absen = nomerEditText.getText().toString().trim();

        // Validasi input
        if (username.isEmpty() || password.isEmpty() || kelas.isEmpty() || absen.isEmpty()) {
            Toast.makeText(register.this, "Semua field harus diisi", Toast.LENGTH_SHORT).show();
            return;
        }

        // Enkripsi password sebelum disimpan
        String encryptedPassword = encryptPassword(password);

        // Buat objek data pengguna
        Map<String, Object> user = new HashMap<>();
        user.put("username", username);
        user.put("password", encryptedPassword);  // Menyimpan password yang telah dienkripsi
        user.put("kelas", kelas);
        user.put("absen", absen);
        user.put("id", UUID.randomUUID().toString());  // ID unik untuk pengguna

        // Simpan data pengguna di Firestore
        db.collection("users").document(username)  // Gunakan username sebagai ID dokumen
                .set(user, SetOptions.merge())
                .addOnSuccessListener(aVoid -> {
                    Toast.makeText(register.this, "Registrasi berhasil", Toast.LENGTH_SHORT).show();
                    // Pindah ke halaman login setelah registrasi berhasil
                    startActivity(new Intent(register.this, LOGIN.class));
                    finish();
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(register.this, "Registrasi gagal: " + e.getMessage(), Toast.LENGTH_SHORT).show();
                });
    }

    // Fungsi untuk mengenkripsi password dengan SHA-256
    private String encryptPassword(String password) {
        try {
            MessageDigest digest = MessageDigest.getInstance("SHA-256");
            byte[] hash = digest.digest(password.getBytes());
            StringBuilder hexString = new StringBuilder();
            for (byte b : hash) {
                hexString.append(String.format("%02x", b));
            }
            return hexString.toString(); // Mengembalikan hasil hash dalam bentuk string hex
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
            return null;  // Jika terjadi kesalahan, kembalikan null
        }
    }
    @SuppressLint("MissingSuperCall")
    @Override
    public void onBackPressed() {
        // Mengarahkan ke Home screen atau mengakhiri activity sesuai kebutuhan
        Intent intent = new Intent(register.this, LOGIN.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
        startActivity(intent);
        finish();
    }
}
