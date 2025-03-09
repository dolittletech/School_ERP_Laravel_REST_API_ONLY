import React, { useState } from "react";
import { useUpdateStudentGuardianPanelMutation } from "../../redux/rtk/features/StudentGuardianPanel/StudentGuardianPanelApi"; // Adjust path
import PageTitle from "../page-header/PageHeader"; // Adjust path
import { Button, Card, Checkbox, Form, Radio, Row } from "antd";
import { toastHandler } from "../../utils/functions"; // Adjust path

const StudentGuardianPanel = () => {
  const [form] = Form.useForm();
  const [updateStudentGuardianPanel, { isLoading, error, data }] = useUpdateStudentGuardianPanelMutation();

  // Handle form submission
  const onFinish = async (values) => {
    const formData = new FormData();
    formData.append("student_panel_login", values.user_login_option === "student" ? "1" : "0"); // String "1"/"0"
    formData.append("parent_panel_login", values.user_login_option === "parent" ? "1" : "0"); // String "1"/"0"
    const studentOptions = values.student_additional_username_options || [];
    const parentOptions = values.parent_additional_username_options || [];
    studentOptions.forEach((option) => formData.append("student_login_options[]", option)); // Ensure array
    if (studentOptions.length === 0) formData.append("student_login_options[]", ""); // Ensure presence
    parentOptions.forEach((option) => formData.append("parent_login_options[]", option)); // Ensure array
    if (parentOptions.length === 0) formData.append("parent_login_options[]", ""); // Ensure presence
    formData.append("student_timeline", values.allow_student_timeline || "disabled");

    // Debug the payload
    const debugData = {};
    for (let pair of formData.entries()) {
      debugData[pair[0]] = debugData[pair[0]] ? [...debugData[pair[0]], pair[1]] : pair[1];
    }
    console.log("FormData:", debugData);

    try {
      const resp = await updateStudentGuardianPanel(formData).unwrap();
      if (resp.data && !resp.error) {
        toastHandler("Student/Guardian panel settings updated successfully", "success");
      }
    } catch (err) {
      console.error("API Error:", JSON.stringify(err, null, 2)); // Log full error with formatting
      toastHandler(err?.data?.errors || err?.data?.error || "Failed to update settings, please try again", "warning");
    }
  };

  return (
    <div
      style={{
        backgroundColor: "#f5f5f5",
        padding: "20px",
        minHeight: "100vh",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
      }}
    >
      <PageTitle title="Back" style={{ marginBottom: "20px" }} />
      <h1
        style={{
          fontSize: "24px",
          fontWeight: "bold",
          marginBottom: "20px",
          textAlign: "center",
          color: "#333",
        }}
      >
        Student / Guardian Panel
      </h1>
      <Card
        bordered
        style={{
          width: "100%",
          maxWidth: "600px",
          borderRadius: "5px",
          boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
          padding: "20px",
        }}
      >
        <Form
          form={form}
          name="studentGuardianPanel"
          onFinish={onFinish}
          layout="vertical"
          style={{ display: "flex", flexDirection: "column", gap: "15px" }}
          initialValues={{
            user_login_option: "student",
            student_additional_username_options: [],
            parent_additional_username_options: [],
            allow_student_timeline: "disabled",
          }}
        >
          <Form.Item label="User Login Option">
            <Radio.Group name="user_login_option">
              <Radio value="student" style={{ marginRight: "20px" }}>Student Login</Radio>
              <Radio value="parent">Parent Login</Radio>
            </Radio.Group>
          </Form.Item>

          <Form.Item label="Additional Username Option For Student Login">
            <Checkbox.Group name="student_additional_username_options">
              <Checkbox value="admission_no" style={{ marginRight: "20px" }}>Admission No</Checkbox>
              <Checkbox value="mobile_number" style={{ marginRight: "20px" }}>Mobile Number</Checkbox>
              <Checkbox value="email">Email</Checkbox>
            </Checkbox.Group>
          </Form.Item>

          <Form.Item label="Additional Username Option For Parent Login">
            <Checkbox.Group name="parent_additional_username_options">
              <Checkbox value="mobile_number" style={{ marginRight: "20px" }}>Mobile Number</Checkbox>
              <Checkbox value="email">Email</Checkbox>
            </Checkbox.Group>
          </Form.Item>

          <Form.Item label="Allow Student To Add Timeline">
            <Radio.Group name="allow_student_timeline">
              <Radio value="disabled" style={{ marginRight: "20px" }}>Disabled</Radio>
              <Radio value="enabled">Enabled</Radio>
            </Radio.Group>
          </Form.Item>

          <Form.Item style={{ textAlign: "right" }}>
            <Button
              type="default"
              htmlType="submit"
              style={{ backgroundColor: "#6c757d", color: "#fff", border: "none" }}
            >
              Save
            </Button>
          </Form.Item>
        </Form>
      </Card>
      {error && (
        <p style={{ color: "red", marginTop: "10px", textAlign: "center" }}>
          Error: {JSON.stringify(error.data?.errors) || error.data?.error || "Update failed"}
        </p>
      )}
      {data && (
        <div style={{ textAlign: "center", marginTop: "20px" }}>
          <p style={{ color: "#333", marginBottom: "10px" }}>{data.message}</p>
        </div>
      )}
    </div>
  );
};

export default StudentGuardianPanel;