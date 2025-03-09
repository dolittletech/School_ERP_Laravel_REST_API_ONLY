import React, { useState } from "react";
import { useUpdateMobileAppMutation, useRegisterAndroidAppMutation } from "../../redux/rtk/features/MobileApp/MobileAppApi"; // Adjust path
import PageTitle from "../page-header/PageHeader"; // Adjust path
import { Button, Card, Form, Input, Modal, Row } from "antd";
import { toastHandler } from "../../utils/functions"; // Adjust path

const { TextArea } = Input;

const MobileApp = () => {
  const [form] = Form.useForm();
  const [modalForm] = Form.useForm(); // Separate form for modal
  const [isModalVisible, setIsModalVisible] = useState(false);
  const [updateMobileApp, { isLoading: updateLoading, error: updateError, data: updateData }] = useUpdateMobileAppMutation();
  const [registerAndroidApp, { isLoading: registerLoading, error: registerError, data: registerData }] = useRegisterAndroidAppMutation();

  // Handle form submission for mobile app settings
  const onFinish = async (values) => {
    const formData = new FormData();
    formData.append("mobile_api_url", values.mobile_api_url || "");
    formData.append("app_primary_color_code", values.app_primary_color_code || "");
    formData.append("app_secondary_color_code", values.app_secondary_color_code || "");

    console.log("FormData:", Object.fromEntries(formData)); // Debug payload
    try {
      const resp = await updateMobileApp(formData).unwrap();
      if (resp.data && !resp.error) {
        toastHandler("Mobile app settings updated successfully", "success");
      }
    } catch (err) {
      console.error("API Error:", JSON.stringify(err, null, 2)); // Log full error
      toastHandler(err?.data?.errors || err?.data?.error || "Failed to update mobile app settings, please try again", "warning");
    }
  };

  // Handle modal open
  const showModal = () => {
    setIsModalVisible(true);
  };

  // Handle modal form submission
  const handleRegister = async (values) => {
    const formData = new FormData();
    formData.append("envato_purchase_code", values.envato_purchase_code || "");
    formData.append("envato_email", values.envato_email || "");

    console.log("Modal FormData:", Object.fromEntries(formData)); // Debug payload
    try {
      const resp = await registerAndroidApp(formData).unwrap();
      if (resp.data && !resp.error) {
        toastHandler("Android app registered successfully", "success");
        setIsModalVisible(false);
        modalForm.resetFields(); // Clear modal form
      }
    } catch (err) {
      console.error("API Error:", JSON.stringify(err, null, 2)); // Log full error
      toastHandler(err?.data?.errors || err?.data?.error || "Failed to register Android app, please try again", "warning");
    }
  };

  // Handle modal cancel
  const handleCancel = () => {
    setIsModalVisible(false);
    modalForm.resetFields(); // Clear modal form on cancel
  };

  return (
    <>
      <PageTitle title="Back" />
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
        <h1
          style={{
            fontSize: "24px",
            fontWeight: "bold",
            marginBottom: "20px",
            textAlign: "center",
            color: "#333",
          }}
        >
          Mobile App
        </h1>
        <Card
          bordered
          style={{
            width: "100%",
            borderRadius: "5px",
            boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
            padding: "20px",
          }}
        >
          <Form
            form={form}
            name="mobileApp"
            onFinish={onFinish}
            layout="vertical"
            style={{ gap: "15px" }}
          >
            <Form.Item>
                <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", width: "100%" }}>
                  <span style={{ fontSize: "17px", fontWeight: "600", color: "#333" }}>User Mobile App</span>
                  <Button
                    type="primary"
                    onClick={showModal}
                    style={{ backgroundColor: "#2094fc", color: "#fff", border: "none"}}
                  >
                    Register Your Android App
                  </Button>
                </div>
            </Form.Item>
            <Form.Item label="User Mobile App API URL" name="mobile_api_url">
              <Input />
            </Form.Item>
            <Form.Item label="User Mobile App Primary Color Code" name="app_primary_color_code">
              <Input placeholder="#424242" />
            </Form.Item>
            <Form.Item label="User Mobile App Secondary Color Code" name="app_secondary_color_code">
              <Input placeholder="#eeeeee" />
            </Form.Item>
            <Form.Item style={{ textAlign: "right" }}>
              <Button
                type="default"
                htmlType="submit"
                style={{ backgroundColor: "#2094fc", color: "#fff", border: "none" }}
              >
                Save
              </Button>
            </Form.Item>
          </Form>
        </Card>
        <Modal
          title="Register your Android App purchase code"
          visible={isModalVisible}
          onOk={() => modalForm.submit()}
          onCancel={handleCancel}
          okText="Save"
          cancelText="Cancel"
          style={{ top: "20px" }}
        >
          <Form
            form={modalForm}
            name="registerAndroidApp"
            onFinish={handleRegister}
            layout="vertical"
          >
            <Form.Item
              label="Envato Market Purchase Code For Smart School Android App (How To Find It?)"
              name="envato_purchase_code"
              rules={[{ required: true, message: "Please input the purchase code!" }]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              label="Your Email Registered With Envato"
              name="envato_email"
              rules={[{ required: true, message: "Please input your email!" }]}
            >
              <Input />
            </Form.Item>
          </Form>
        </Modal>
        {updateError && (
          <p style={{ color: "red", marginTop: "10px", textAlign: "center" }}>
            Error: {updateError.data?.errors || updateError.data?.error || "Update failed"}
          </p>
        )}
        {registerError && (
          <p style={{ color: "red", marginTop: "10px", textAlign: "center" }}>
            Error: {registerError.data?.errors || registerError.data?.error || "Registration failed"}
          </p>
        )}
        {updateData && (
          <div style={{ textAlign: "center", marginTop: "20px" }}>
            <p style={{ color: "#333", marginBottom: "10px" }}>{updateData.message}</p>
          </div>
        )}
        {registerData && (
          <div style={{ textAlign: "center", marginTop: "20px" }}>
            <p style={{ color: "#333", marginBottom: "10px" }}>{registerData.message}</p>
          </div>
        )}
      </div>
    </>
  );
};

export default MobileApp;