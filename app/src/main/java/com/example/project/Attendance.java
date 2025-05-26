package com.example.project;

public class Attendance {
    private String name;
    private String status;
    private String locationString;
    private String base64Image; // Menambahkan atribut untuk menyimpan gambar dalam format Base64
    private String timestamp;
    private String kelas;

    // Konstruktor kosong diperlukan oleh Firestore
    public Attendance() {
    }

    // Konstruktor dengan semua parameter
    public Attendance(String name, String status, String location, String base64Image, String timestamp, String kelas) {
        this.name = name;
        this.status = status;
        this.locationString = location;
        this.base64Image = base64Image; // Inisialisasi atribut gambar
        this.timestamp = timestamp;
        this.kelas = kelas;
    }

    // Getter dan Setter untuk name
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    // Getter dan Setter untuk status
    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    // Getter dan Setter untuk location
    public String getLocation() {
        return locationString;
    }

    public void setLocation(String location) {
        this.locationString = location;
    }

    // Getter dan Setter untuk base64Image
    public String getBase64Image() {
        return base64Image;
    }

    public void setBase64Image(String base64Image) {
        this.base64Image = base64Image;
    }

    // Getter dan Setter untuk timestamp
    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
    }

    // Getter dan Setter untuk kelas
    public String getKelas() {
        return kelas;
    }

    public void setKelas(String kelas) {
        this.kelas = kelas;
    }
}
