import React, { useEffect, useState, useContext } from 'react';
import { Link } from 'react-router-dom';
import { AuthContext } from '../../context/AuthContext';

const UserList = () => {
  const [users, setUsers] = useState([]);
  const [errorMessage, setErrorMessage] = useState('');
  const { user } = useContext(AuthContext);

  // Fetch users when component mounts
  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const response = await fetch('http://localhost/Smart-Retail-Store-Management-System/php-api/users/get_users.php', {
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${user.token}`, // ส่ง Token ในรูปแบบ Bearer
          },
        });

        const data = await response.json();

        if (data.status === 'success') {
          setUsers(data.users); // เก็บข้อมูลผู้ใช้ใน State
        } else {
          setErrorMessage(data.message); // เก็บข้อความข้อผิดพลาด
        }
      } catch (error) {
        setErrorMessage('เกิดข้อผิดพลาดในการดึงข้อมูลผู้ใช้');
        console.error('Error fetching users:', error);
      }
    };

    fetchUsers();
  }, [user.token]);

  // Handle delete user
  const handleDelete = async (id) => {
    if (window.confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?')) {
      try {
        const response = await fetch('http://localhost/Smart-Retail-Store-Management-System/php-api/users/delete_user.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${user.token}`,
          },
          body: JSON.stringify({ id }),
        });

        const data = await response.json();

        if (data.status === 'success') {
          setUsers(users.filter((u) => u.id !== id)); // อัปเดต State หลังจากลบสำเร็จ
        } else {
          setErrorMessage(data.message); // แสดงข้อผิดพลาด
        }
      } catch (error) {
        setErrorMessage('เกิดข้อผิดพลาดในการลบผู้ใช้');
        console.error('Error deleting user:', error);
      }
    }
  };

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-semibold">จัดการผู้ใช้</h2>
        <Link to="/users/add" className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          เพิ่มผู้ใช้
        </Link>
      </div>

      {errorMessage && <p className="text-red-500 mb-4">{errorMessage}</p>}

      <table className="w-full table-auto border-collapse">
        <thead>
          <tr className="bg-gray-200 text-left">
            <th className="px-4 py-2 border">ชื่อผู้ใช้</th>
            <th className="px-4 py-2 border">อีเมล</th>
            <th className="px-4 py-2 border">บทบาท</th>
            <th className="px-4 py-2 border">การกระทำ</th>
          </tr>
        </thead>
        <tbody>
          {users.length > 0 ? (
            users.map((u) => (
              <tr key={u.id} className="border-b">
                <td className="px-4 py-2 border">{u.username}</td>
                <td className="px-4 py-2 border">{u.email}</td>
                <td className="px-4 py-2 border">{u.role}</td>
                <td className="px-4 py-2 border">
                  <Link
                    to={`/users/edit/${u.id}`}
                    className="bg-yellow-500 text-white px-2 py-1 rounded mr-2 hover:bg-yellow-600 transition"
                  >
                    แก้ไข
                  </Link>
                  <button
                    onClick={() => handleDelete(u.id)}
                    className="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition"
                  >
                    ลบ
                  </button>
                </td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="4" className="px-4 py-2 text-center">
                ไม่พบข้อมูลผู้ใช้
              </td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
};

export default UserList;
