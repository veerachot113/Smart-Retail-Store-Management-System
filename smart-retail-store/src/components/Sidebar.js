// src/components/Sidebar.js
import React from 'react';
import { NavLink } from 'react-router-dom';

const Sidebar = ({ role }) => {
  return (
    <div className="w-64 bg-gray-800 text-white min-h-screen">
      <div className="p-4">
        <h2 className="text-xl font-semibold">Smart Store</h2>
      </div>
      <nav>
        <NavLink to="/dashboard" className="block px-4 py-2 hover:bg-gray-700">
          แดชบอร์ด
        </NavLink>
        {role === 'admin' && (
          <>
            <NavLink to="/users" className="block px-4 py-2 hover:bg-gray-700">
              จัดการผู้ใช้
            </NavLink>
            {/* เมนูอื่น ๆ สำหรับแอดมิน */}
          </>
        )}
        {role === 'staff' && (
          <>
            <NavLink to="/pos" className="block px-4 py-2 hover:bg-gray-700">
              ขายสินค้า
            </NavLink>
            {/* เมนูอื่น ๆ สำหรับพนักงาน */}
          </>
        )}
      </nav>
    </div>
  );
};

export default Sidebar;
