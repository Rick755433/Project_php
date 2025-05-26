package com.example.project;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.util.Base64;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

public class AttendanceAdapter extends RecyclerView.Adapter<AttendanceAdapter.AttendanceViewHolder> {

    private List<Attendance> attendanceList;

    public AttendanceAdapter(List<Attendance> attendanceList) {
        this.attendanceList = attendanceList;
    }

    @Override
    public AttendanceViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.attendance_item, parent, false);
        return new AttendanceViewHolder(view);
    }

    @Override
    public void onBindViewHolder(AttendanceViewHolder holder, int position) {
        Attendance attendance = attendanceList.get(position);

        holder.textViewStatus.setText(attendance.getStatus());
        holder.textViewTimestamp.setText(attendance.getTimestamp());

        // Decode Base64 image if available
        if (attendance.getBase64Image() != null && !attendance.getBase64Image().isEmpty()) {
            Bitmap bitmap = decodeBase64(attendance.getBase64Image());
            holder.imageView.setImageBitmap(bitmap);
        }
    }

    @Override
    public int getItemCount() {
        return attendanceList.size();
    }

    private Bitmap decodeBase64(String base64String) {
        byte[] decodedBytes = Base64.decode(base64String, Base64.DEFAULT);
        return BitmapFactory.decodeByteArray(decodedBytes, 0, decodedBytes.length);
    }

    public static class AttendanceViewHolder extends RecyclerView.ViewHolder {
        TextView textViewStatus, textViewTimestamp;
        ImageView imageView;

        public AttendanceViewHolder(View itemView) {
            super(itemView);
            textViewStatus = itemView.findViewById(R.id.textViewStatus);
            textViewTimestamp = itemView.findViewById(R.id.textViewTimestamp);
            imageView = itemView.findViewById(R.id.imageViewAttendance);
        }
    }
}
