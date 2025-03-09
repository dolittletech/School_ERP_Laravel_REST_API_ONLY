import {
  CalendarOutlined,
  ClockCircleOutlined,
  FileDoneOutlined,
  FileOutlined,
  FileSyncOutlined,
  FlagFilled,
  FlagOutlined,
  HomeOutlined,
  NotificationFilled,
  PieChartFilled,
  RocketOutlined,
  SettingOutlined,
  SubnodeOutlined,
  TrophyFilled,
  UnorderedListOutlined,
  UserOutlined,
  UserSwitchOutlined,
  UsergroupAddOutlined,
  UsergroupDeleteOutlined,
  WalletOutlined,
} from "@ant-design/icons";
import { Menu } from "antd";
import React from "react";
import { NavLink } from "react-router-dom";
import getPermissions from "../../utils/getPermissions";
import getUserFromToken from "../../utils/getUserFromToken";

const Sidenav = ({ color, sideNavOpenKeys }) => {
  const user = getUserFromToken();
  const permissions = getPermissions();
  const hasPermission = (item) => {
    return permissions?.includes(item ? item : "");
  };

  const menu = [
    {
      label: (
        <NavLink to="/admin/dashboard">
          <span>Dashboard</span>
        </NavLink>
      ),
      key: "dashboard",
      icon: <HomeOutlined />,
    },

    (hasPermission("create-user") ||
      hasPermission("readAll-user") ||
      hasPermission("readAll-role") ||
      hasPermission("readAll-designation") ||
      hasPermission("readAll-department")) && {
      label: "HR",
      key: "hr",
      icon: <UserOutlined />,
      children: [
        hasPermission("create-user") && {
          label: (
            <NavLink to="/admin/hr/staffs/new">
              <span>New Employee</span>
            </NavLink>
          ),
          key: "staffs",
          icon: <UsergroupAddOutlined />,
        },
        hasPermission("readAll-user") && {
          label: (
            <NavLink to="/admin/hr/staffs">
              <span>Employee List</span>
            </NavLink>
          ),
          key: "users",
          icon: <UsergroupAddOutlined />,
        },
        hasPermission("readAll-role") && {
          label: (
            <NavLink to="/admin/role">
              <span>Role & Permissions</span>
            </NavLink>
          ),
          key: "roleAndPermissions",
          icon: <UserSwitchOutlined />,
        },
        hasPermission("readAll-designation") && {
          label: (
            <NavLink to="/admin/designation/">
              <span>Designation</span>
            </NavLink>
          ),
          key: "designation",
          icon: <UserSwitchOutlined />,
        },
        hasPermission("readAll-department") && {
          label: (
            <NavLink to="/admin/department">
              <span>Department</span>
            </NavLink>
          ),
          key: "department",
          icon: <UserSwitchOutlined />,
        },
      ],
    },

    (hasPermission("create-attendance") ||
      hasPermission("readAll-attendance")) && {
      label: "ATTENDANCE",
      key: "ATTENDANCE",
      icon: <ClockCircleOutlined />,
      children: [
        hasPermission("create-attendance") && {
          label: (
            <NavLink to="/admin/attendance">
              <span>Attendance</span>
            </NavLink>
          ),
          key: "attendance",
          icon: <FileDoneOutlined />,
        },
        hasPermission("readSingle-attendance") && {
          label: (
            <NavLink to={`/admin/attendance/user/${user}`}>
              <span>My Attendance</span>
            </NavLink>
          ),
          key: "myAttendance",
          icon: <FileDoneOutlined />,
        },
      ],
    },

    (hasPermission("create-payroll") || hasPermission("readAll-payroll")) && {
      label: "PAYROLL",
      key: "payroll",
      icon: <WalletOutlined />,
      children: [
        hasPermission("create-payroll") && {
          label: (
            <NavLink to="/admin/payroll/new">
              <span>Calculate Payroll</span>
            </NavLink>
          ),
          key: "calculatePayroll",
          icon: <FileDoneOutlined />,
        },
        hasPermission("readAll-payroll") && {
          label: (
            <NavLink to="/admin/payroll/list">
              <span>Payslip List</span>
            </NavLink>
          ),
          key: "payslipList",
          icon: <FileOutlined />,
        },
      ],
    },

    hasPermission("readAll-shift") && {
      label: "SHIFT",
      key: "shift",
      icon: <ClockCircleOutlined />,
      children: [
        hasPermission("readAll-shift") && {
          label: (
            <NavLink to="/admin/shift">
              <span>Shift</span>
            </NavLink>
          ),
          key: "newShift",
          icon: <FileDoneOutlined />,
        },
      ],
    },

    hasPermission("readAll-employmentStatus") && {
      label: "EMPLOYMENT",
      key: "EMPLOYMENT",
      icon: <RocketOutlined />,
      children: [
        hasPermission("readAll-employmentStatus") && {
          label: (
            <NavLink to="/admin/employment-status">
              <span>Status</span>
            </NavLink>
          ),
          key: "employmentStatus",
          icon: <FileDoneOutlined />,
        },
      ],
    },

    (hasPermission("create-leaveApplication") ||
      hasPermission("readAll-leaveApplication") ||
      hasPermission("readSingle-leaveApplication")) && {
      label: "LEAVE ",
      key: "leave",
      icon: <UsergroupDeleteOutlined />,
      children: [
        hasPermission("create-leaveApplication") && {
          label: (
            <NavLink to="/admin/leave/new">
              <span> New Leave </span>
            </NavLink>
          ),
          key: "newLeave",
          icon: <SubnodeOutlined />,
        },
        hasPermission("readAll-leaveApplication") && {
          label: (
            <NavLink to="/admin/leave">
              <span>Leave Status</span>
            </NavLink>
          ),
          key: "leaveStatus",
          icon: <FileDoneOutlined />,
        },
        hasPermission("readSingle-leaveApplication") && {
          label: (
            <NavLink to={`/admin/leave/user/${user}`}>
              <span>My Leaves</span>
            </NavLink>
          ),
          key: "myLeaves",
          icon: <FileDoneOutlined />,
        },
      ],
    },

    (hasPermission("readAll-weeklyHoliday") ||
      hasPermission("readAll-publicHoliday")) && {
      label: "HOLIDAY",
      key: "holiday",
      icon: <CalendarOutlined />,
      children: [
        hasPermission("readAll-weeklyHoliday") && {
          label: (
            <NavLink to="/admin/holiday/week">
              <span>Weekly Holiday</span>
            </NavLink>
          ),
          key: "weeklyHoliday",
          icon: <PieChartFilled />,
        },
        hasPermission("readAll-publicHoliday") && {
          label: (
            <NavLink to="/admin/holiday/public">
              <span>Public Holiday</span>
            </NavLink>
          ),
          key: "publicHoliday",
          icon: <PieChartFilled />,
        },
      ],
    },

    hasPermission("readAll-leavePolicy") && {
      label: "LEAVE POLICY",
      key: "LEAVE POLICY",
      icon: <CalendarOutlined />,
      children: [
        hasPermission("readAll-leavePolicy") && {
          label: (
            <NavLink to="/admin/leave-policy">
              <span>Leave Policy</span>
            </NavLink>
          ),
          key: "leavePolicy",
          icon: <PieChartFilled />,
        },
      ],
    },

    hasPermission("readAll-announcement") && {
      label: "ANNOUNCEMENT",
      key: "ANNOUNCEMENT",
      icon: <NotificationFilled />,
      children: [
        hasPermission("readAll-announcement") && {
          label: (
            <NavLink to="/admin/announcement">
              <span>Announcement</span>
            </NavLink>
          ),
          key: "announcement",
          icon: <FlagFilled />,
        },
      ],
    },

    (hasPermission("readAll-account") ||
      hasPermission("readAll-transaction") ||
      hasPermission("create-transaction")) && {
      label: "ACCOUNTS",
      key: "accounts",
      icon: <WalletOutlined />,
      children: [
        hasPermission("readAll-account") && {
          label: (
            <NavLink to="/admin/account/">
              <span>Account</span>
            </NavLink>
          ),
          key: "accountList",
          icon: <UnorderedListOutlined />,
        },

        (hasPermission("readAll-transaction") ||
          hasPermission("create-transaction")) && {
          label: (
            <NavLink to="/admin/transaction/">
              <span>Transaction</span>
            </NavLink>
          ),
          key: "transaction",
          icon: <UnorderedListOutlined />,
        },
      ],
    },

    hasPermission("readAll-account") && {
      label: "FINANCE REPORT",
      key: "report",
      icon: <FlagOutlined />,
      children: [
        hasPermission("readAll-account") && {
          label: (
            <NavLink to="/admin/account/trial-balance">
              <span>Trial Balance</span>
            </NavLink>
          ),
          key: "trialBalance",
          icon: <FileDoneOutlined />,
        },
        hasPermission("readAll-account") && {
          label: (
            <NavLink to="/admin/account/balance-sheet">
              <span>Balance Sheet</span>
            </NavLink>
          ),
          key: "balanceSheet",
          icon: <FileOutlined />,
        },
        hasPermission("readAll-account") && {
          label: (
            <NavLink to="/admin/account/income">
              <span>Income Statement</span>
            </NavLink>
          ),
          key: "incomeStatement",
          icon: <FileSyncOutlined />,
        },
      ],
    },

    // Replaced AWARDS with General Setting
    hasPermission("readAll-setting") && {
      label: "Settings",
      key: "generalSetting",
      icon: <SettingOutlined />,
      children: [
        {
          label: (
            <NavLink to="/admin/general-setting">
              <span>General Setting</span>
            </NavLink>
          ),
          key: "logo",
          icon: <SettingOutlined />,
        },
        {
          label: (
            <NavLink to="/admin/logo">
              <span>Logo</span>
            </NavLink>
          ),
          key: "logo",
          icon: <SettingOutlined />,
        },
        {
          label: (
            <NavLink to="/admin/backend-theme">
              <span>Backend Theme</span>
            </NavLink>
          ),
          key: "backendTheme",
          icon: <SettingOutlined />,
        },
        {
          label: (
            <NavLink to="/admin/mobile-app">
              <span>Mobile App</span>
            </NavLink>
          ),
          key: "mobileApp",
          icon: <SettingOutlined />,
        },
      ],
    },
  ].filter(Boolean); // Remove undefined items due to permission checks

  return (
    <div>
      <Menu
        theme="dark"
        mode="inline"
        items={menu}
        className="sidenav-menu "
      />
    </div>
  );
};

export default Sidenav;