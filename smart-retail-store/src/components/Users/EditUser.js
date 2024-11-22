// src/components/Users/EditUser.js
import React, { useState, useEffect, useContext } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { AuthContext } from '../../context/AuthContext';

const EditUser = () => {
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('staff');
  const [errorMessage, setErrorMessage] = useState('');
  const navigate = useNavigate();
  const { id } = useParams();
  const { user } = useContext(AuthContext);

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const response = await fetch(`http://localhost/api/users/get_user.php?id=${id}`, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: user.token,
          },
        });

        const data = await response.json();

        if (data.status === 'success') {
          setUsername(data.user.username);
          setEmail(data.user.email);
          setRole(data.user.role);
        } else {
          setErrorMessage(data.message);
        }
      } catch (error) {
        console.error('Error fetching user:', error);
        setErrorMessage('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
      }
    };

    fetchUser();
  }, [id, user.token]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('http://localhost/api/users/edit_user.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: user.token,
        },
        body: JSON.stringify({ id, username, email, role }),
      });

      const data = await response.json();

      if (data.status === 'success') {
        navigate('/users');
      } else {
        setErrorMessage(data.message);
      }
    } catch (error) {
      console.error('Error updating user:', error);
      setErrorMessage('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
    }
  };

  return (
    <div className="p-6">
      <h2 className="text-2xl font-semibold mb-4">แก้ไขผู้ใช้</h2>
      {errorMessage && <p className="text-red-500 mb-4">{errorMessage}</p>}
      <form onSubmit={handleSubmit} className="max-w-md">
        <div className="mb-4">
          <label className="block text-sm font-medium">ชื่อผู้ใช้</label>
          <input
            type="text"
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block text-sm font-medium">อีเมล</label>
          <input
            type="email"
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div className="mb-4">
          <label className="block text-sm font-medium">บทบาท</label>
          <select
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={role}
            onChange={(e) => setRole(e.target.value)}
          >
            <option value="admin">แอดมิน</option>
            <option value="staff">พนักงาน</option>
          </select>
        </div>
        <button
          type="submit"
          className="bg-blue-600 text-white px-4 py-2 rounded"
        >
          บันทึกการเปลี่ยนแปลง
        </button>
      </form>
    </div>
  );
};

export default EditUser;
