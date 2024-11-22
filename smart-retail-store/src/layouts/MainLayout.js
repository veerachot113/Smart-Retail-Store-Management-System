// src/layouts/MainLayout.js
import React, { useContext } from 'react';
import { Outlet } from 'react-router-dom';
import Sidebar from '../components/Sidebar';
import { AuthContext } from '../context/AuthContext';

const MainLayout = () => {
  const { user } = useContext(AuthContext);

  return (
    <div className="flex">
      <Sidebar role={user.role} />
      <div className="flex-grow">
        {/* ส่วนหัวของแอปพลิเคชัน */}
        <Outlet />
      </div>
    </div>
  );
};

export default MainLayout;
