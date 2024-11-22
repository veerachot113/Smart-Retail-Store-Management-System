import React from 'react';
import { 
  ShoppingCart, 
  Users, 
  DollarSign, 
  TrendingUp, 
  PackageOpen 
} from 'lucide-react';

const Dashboard = () => {
  // จำลองข้อมูล (ในอนาคตจะดึงจาก Backend)
  const dashboardStats = [
    {
      icon: <ShoppingCart />,
      title: "ยอดขายวันนี้",
      value: "฿45,250",
      change: "+12.5%"
    },
    {
      icon: <Users />,
      title: "ลูกค้าใหม่",
      value: "45 คน",
      change: "+8%"
    },
    {
      icon: <DollarSign />,
      title: "รายได้รวม",
      value: "฿250,500",
      change: "+15.2%"
    },
    {
      icon: <PackageOpen />,
      title: "สินค้าคงคลัง",
      value: "256 ชิ้น",
      change: "-5%"
    }
  ];

  return (
    <div className="min-h-screen bg-gray-100 p-8">
      <div className="bg-white p-8 rounded-lg shadow-lg">
        <h2 className="text-2xl font-semibold mb-4">ยินดีต้อนรับเข้าสู่ระบบ Smart Retail Store</h2>
        
        {/* Grid สถิติ */}
        <div className="grid grid-cols-4 gap-4 mt-6">
          {dashboardStats.map((stat, index) => (
            <div 
              key={index} 
              className="bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center"
            >
              <div className="mr-4 text-blue-500">
                {stat.icon}
              </div>
              <div>
                <p className="text-sm text-gray-500">{stat.title}</p>
                <p className="text-lg font-semibold">{stat.value}</p>
                <p className={`text-xs ${stat.change.startsWith('+') ? 'text-green-500' : 'text-red-500'}`}>
                  {stat.change}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* ส่วนแสดงกราฟหรือตารางเพิ่มเติม */}
        <div className="mt-8">
          <h3 className="text-xl font-semibold mb-4">ภาพรวมการขาย</h3>
          {/* เอาไว้ใส่กราฟหรือตารางในอนาคต */}
          <p className="text-gray-500">กำลังโหลดข้อมูล...</p>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;