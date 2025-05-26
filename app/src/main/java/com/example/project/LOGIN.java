package com.example.project;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class LOGIN extends AppCompatActivity {

    private EditText usernameEditText, passwordEditText;
    private TextView link;
    private Button loginButton;
    private FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        db = FirebaseFirestore.getInstance();
        link = findViewById(R.id.link);

        // Inisialisasi Views
        usernameEditText = findViewById(R.id.intusername);
        passwordEditText = findViewById(R.id.intpasword);
        loginButton = findViewById(R.id.btnlogin);

        // Tombol login
        loginButton.setOnClickListener(view -> loginUser());
        link.setOnClickListener(view -> ganti());
    }

    private void ganti() {
        Intent intent = new Intent(LOGIN.this, register.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
        finish();
    }

    private void loginUser() {
        String username = usernameEditText.getText().toString().trim();
        String password = passwordEditText.getText().toString().trim();

        if (username.isEmpty() || password.isEmpty()) {
            Toast.makeText(LOGIN.this, "Username dan password harus diisi", Toast.LENGTH_SHORT).show();
            return;
        }

        // Enkripsi password yang dimasukkan oleh pengguna
        String encryptedPassword = encryptPassword(password);

        db.collection("users")
                .whereEqualTo("username", username)
                .get()
                .addOnSuccessListener(querySnapshot -> {
                    if (!querySnapshot.isEmpty()) {
                        DocumentSnapshot documentSnapshot = querySnapshot.getDocuments().get(0);

                        // Ambil password yang disimpan (sudah di-enkripsi) dari Firestore
                        String storedPassword = documentSnapshot.getString("password");
                        String userId = documentSnapshot.getId();  // Mengambil ID dokumen sebagai userId

                        // Cocokkan password yang sudah dienkripsi
                        if (storedPassword != null && storedPassword.equals(encryptedPassword)) {
                            SharedPreferences sharedPreferences = getSharedPreferences("UserSession", Context.MODE_PRIVATE);
                            SharedPreferences.Editor editor = sharedPreferences.edit();
                            editor.putBoolean("isLoggedIn", true);
                            editor.putString("userId", userId); // Menyimpan userId
                            editor.apply();

                            Toast.makeText(LOGIN.this, "Login berhasil", Toast.LENGTH_SHORT).show();
                            Intent intent = new Intent(LOGIN.this, MainActivity.class);
                            startActivity(intent);
                            finish();
                        } else {
                            Toast.makeText(LOGIN.this, "Password salah", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Toast.makeText(LOGIN.this, "Username tidak ditemukan", Toast.LENGTH_SHORT).show();
                    }
                })
                .addOnFailureListener(e -> Toast.makeText(LOGIN.this, "Login gagal: " + e.getMessage(), Toast.LENGTH_SHORT).show());
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
}
