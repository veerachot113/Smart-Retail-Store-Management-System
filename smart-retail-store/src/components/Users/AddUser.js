import React, { useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

const AddUser = () => {
  const [username, setUsername] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("staff");
  const [errorMessage, setErrorMessage] = useState("");
  const navigate = useNavigate();
  const { user } = useContext(AuthContext); // Use context for token

  const handleSubmit = async (e) => {
    e.preventDefault();

    console.log("Token being sent:", user.token); // Debug Token

    try {
      const response = await fetch(
        "http://localhost/Smart-Retail-Store-Management-System/php-api/users/add_user.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${user.token}`, // Include Token in Header
          },
          body: JSON.stringify({ username, email, password, role }),
        }
      );

      const data = await response.json();
      console.log("Response from API:", data); // Debug API Response

      if (data.status === "success") {
        navigate("/users"); // Redirect to User List
      } else {
        setErrorMessage(data.message); // Show error message
      }
    } catch (error) {
      console.error("Error adding user:", error);
      setErrorMessage("เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์");
    }
  };

  return (
    <div className="p-6">
      <h2 className="text-2xl font-semibold mb-4">เพิ่มผู้ใช้</h2>
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
          <label className="block text-sm font-medium">รหัสผ่าน</label>
          <input
            type="password"
            className="mt-1 p-2 w-full border border-gray-300 rounded"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            minLength={6}
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
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200"
        >
          บันทึก
        </button>
      </form>
    </div>
  );
};

export default AddUser;
