package com.example.project;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.widget.CalendarView;
import android.widget.Toast;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;

public class kalender extends AppCompatActivity {

    ActionBar actionBar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_kalender);

        // Menyambungkan CalendarView dengan layout
        CalendarView calendarView = findViewById(R.id.calendarView);

        // Menangani event ketika pengguna memilih tanggal
        calendarView.setOnDateChangeListener(new CalendarView.OnDateChangeListener() {
            @Override
            public void onSelectedDayChange(CalendarView view, int year, int month, int dayOfMonth) {
                // Mengonversi tanggal yang dipilih menjadi format yang diinginkan
                String selectedDate = dayOfMonth + "/" + (month + 1) + "/" + year;
                // Menampilkan tanggal yang dipilih dengan Toast
                Toast.makeText(kalender.this, "Tanggal Terpilih: " + selectedDate, Toast.LENGTH_SHORT).show();

                // Mengaktifkan tombol back di ActionBar
                actionBar = getSupportActionBar();
                if (actionBar != null) {
                    actionBar.setDisplayHomeAsUpEnabled(true);
                }
            }
        });
    }

//    @Override
//    public boolean onSupportNavigateUp() {
//        // Ketika tombol back di ActionBar ditekan
////        super.onBackPressed();
//        return true;  // Menandakan bahwa tindakan sudah ditangani
//    }

    @SuppressLint("MissingSuperCall")
    @Override
    public void onBackPressed() {
        // Mengarahkan ke Home screen atau mengakhiri activity sesuai kebutuhan

                    Intent intent = new Intent(kalender.this, MainActivity.class); // Ganti `spas.class` dengan Activity utama Anda
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
