// src/components/Auth/ChangePassword.js
import React, { useState, useContext } from 'react';
import { AuthContext } from '../../context/AuthContext';

const ChangePassword = () => {
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [message, setMessage] = useState('');
  const { user } = useContext(AuthContext);

  const handleChangePassword = async (e) => {
    e.preventDefault();

    if (newPassword !== confirmPassword) {
      setMessage('รหัสผ่านใหม่และการยืนยันไม่ตรงกัน');
      return;
    }

    try {
      const response = await fetch('http://localhost/api/change_password.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: user.token,
        },
        body: JSON.stringify({
          currentPassword,
          newPassword,
        }),
      });

      const data = await response.json();

      if (data.status === 'success') {
        setMessage('เปลี่ยนรหัสผ่านสำเร็จ');
        // ล้างฟอร์ม
        setCurrentPassword('');
        setNewPassword('');
        setConfirmPassword('');
      } else {
        setMessage(data.message);
      }
    } catch (error) {
      console.error('Error changing password:', error);
      setMessage('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
    }
  };

  return (
    <div className="p-6">
      <h2 className="text-2xl font-semibold mb-4">เปลี่ยนรหัสผ่าน</h2>
      {message && <p className="mb-4 text-red-500">{message}</p>}
      <form onSubmit={handleChangePassword} className="max-w-md">
        <div className="mb-4">
          <label className="block text-sm font-medium">รหัสผ่านปัจจุบัน</label>
          <input
            type="password"
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={currentPassword}
            onChange={(e) => setCurrentPassword(e.target.value)}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block text-sm font-medium">รหัสผ่านใหม่</label>
          <input
            type="password"
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={newPassword}
            onChange={(e) => setNewPassword(e.target.value)}
            required
            minLength={6}
          />
        </div>
        <div className="mb-4">
          <label className="block text-sm font-medium">ยืนยันรหัสผ่านใหม่</label>
          <input
            type="password"
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
            required
            minLength={6}
          />
        </div>
        <button
          type="submit"
          className="bg-blue-600 text-white px-4 py-2 rounded"
        >
          เปลี่ยนรหัสผ่าน
        </button>
      </form>
    </div>
  );
};

export default ChangePassword;
