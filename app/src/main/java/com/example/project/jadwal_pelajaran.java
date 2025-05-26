package com.example.project;

import static com.example.project.R.id.tableLayout;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class jadwal_pelajaran extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_jadwal_pelajaran);

        // Mendapatkan referensi ke TableLayout
        TableLayout tableLayout = findViewById(R.id.tableLayout);

        // Data Jadwal Pelajaran (Jam Pelajaran dan Mata Pelajaran)
        String[][] jadwal = {
                {"08:00 - 09:30", "Matematika", "Fisika", "Bahasa Indonesia", "Geografi", "Seni Rupa"},
                {"09:30 - 11:00", "Bahasa Inggris", "Kimia", "Sejarah", "Pendidikan Jasmani", "Musik"},
                {"11:00 - 12:30", "Biologi", "Matematika", "Pendidikan Agama", "Geografi", "Seni Rupa"},
                {"12:30 - 14:00", "Sejarah", "Fisika", "Bahasa Indonesia", "Pendidikan Jasmani", "Seni Budaya"},
                {"14:00 - 15:30", "Kimia", "Matematika", "Bahasa Inggris", "Sejarah", "Olahraga"}
        };



    }
    @SuppressLint("MissingSuperCall")
    @Override
    public void onBackPressed() {
        // Mengarahkan ke Home screen atau mengakhiri activity sesuai kebutuhan

        Intent intent = new Intent(jadwal_pelajaran.this, MainActivity.class); // Ganti `spas.class` dengan Activity utama Anda
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
        startActivity(intent);
        finish();

        // Optional: Memberikan feedback jika ingin pindah ke Home screen
        // Intent homeIntent = new Intent(Intent.ACTION_MAIN);
        // homeIntent.addCategory(Intent.CATEGORY_HOME);
        // homeIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        // startActivity(homeIntent);
    }
}