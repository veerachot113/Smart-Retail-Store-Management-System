// src/App.js
import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Login from './components/Auth/Login';
import ChangePassword from './components/Auth/ChangePassword';
import Dashboard from './components/Dashboard/Dashboard';
import AuthProvider from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import MainLayout from './layouts/MainLayout';
import AuthLayout from './layouts/AuthLayout';

// นำเข้าคอมโพเนนต์เพิ่มเติม
import UserList from './components/Users/UserList';
import AddUser from './components/Users/AddUser';
import EditUser from './components/Users/EditUser';

const App = () => {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* เส้นทางสำหรับผู้ใช้ทั่วไป */}
          <Route element={<AuthLayout />}>
            <Route path="/login" element={<Login />} />
          </Route>

          {/* เส้นทางที่ต้องการการยืนยันตัวตน */}
          <Route
            element={
              <ProtectedRoute>
                <MainLayout />
              </ProtectedRoute>
            }
          >
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/changepassword" element={<ChangePassword />} />

            {/* เส้นทางสำหรับการจัดการผู้ใช้ */}
            <Route path="/users" element={<UserList />} />
            <Route path="/users/add" element={<AddUser />} />
            <Route path="/users/edit/:id" element={<EditUser />} />

            {/* เพิ่มเส้นทางอื่นๆ ที่คุณมี */}
          </Route>

          {/* เส้นทางเริ่มต้นหรือเส้นทางที่ไม่พบ */}
          <Route path="*" element={<Login />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
};

export default App;
