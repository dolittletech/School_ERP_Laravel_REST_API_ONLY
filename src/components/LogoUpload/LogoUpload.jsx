import React, { useState } from "react";
import { useUploadLogoMutation } from "../../redux/rtk/features/LogoUpload/LogoUploadApi"; // Adjust path based on your structure
import PageTitle from "../page-header/PageHeader";
import { UploadOutlined } from "@ant-design/icons";
import { Button, Card, Col, Form, Input, Row, Typography, Upload } from "antd";
import { toastHandler } from "../../utils/functions";

const { TextArea } = Input;
const LogoUpload = () => {
  const [files, setFiles] = useState({
    print_logo: null,
    admin_logo: null,
    admin_small_logo: null,
    app_logo: null,
    admin_login_page_background: null,
    user_login_page_background: null,
  });
  const [uploadLogo, { isLoading, error, data }] = useUploadLogoMutation();

  // Logo type dimensions (for display)
  const logoDimensions = {
    print_logo: { width: 170, height: 184 },
    admin_logo: { width: 290, height: 51 },
    admin_small_logo: { width: 32, height: 32 },
    app_logo: { width: 290, height: 51 },
    admin_login_page_background: { width: 1920, height: 1080 },
    user_login_page_background: { width: 1920, height: 1080 },
  };

  // Handle file selection for each logo type
  const handleFileChange = (logoType, { fileList }) => {
    if (fileList.length > 0) {
      setFiles((prev) => ({ ...prev, [logoType]: fileList[0].originFileObj }));
    } else {
      setFiles((prev) => ({ ...prev, [logoType]: null }));
    }
  };

  // Handle form submission for a specific logo type
  const handleSubmit = async (logoType) => {
    const file = files[logoType];
    if (!file) {
      message.warning("Please select a file to upload!");
      return;
    }

    const formData = new FormData();
    formData.append("file", file);
    formData.append("logo_type", logoType);

    try {
      const resp = await uploadLogo(formData).unwrap();
      if (resp.data && !resp.error) {
        toastHandler("Logo updated successfully", "success");
        setFiles((prev) => ({ ...prev, [logoType]: null })); // Reset file on success
      }
    } catch (err) {
      toastHandler("Upload failed, please try again", "warning");
    }
  };

  // Split logo types into two groups
  const firstFourLogos = ["print_logo", "admin_logo", "admin_small_logo", "app_logo"];
  const lastTwoLogos = ["admin_login_page_background", "user_login_page_background"];

  return (
    <>
      {/* PageTitle at the top */}
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
          Upload Logo
        </h1>
        <Row gutter={[16, 16]} style={{ width: "100%", maxWidth: "1200px" }}>
          {/* Left Side: First Four Logos under "Logo" title */}
          <Col xs={24} md={12}>
            <h2
              style={{
                fontSize: "20px",
                fontWeight: "bold",
                marginBottom: "15px",
                color: "#333",
                textAlign: "center",
              }}
            >
              Logo
            </h2>
            <Row gutter={[16, 16]}>
              {firstFourLogos.map((logoType) => (
                <Col xs={24} sm={12} md={12} key={logoType}>
                  <Card
                    bordered
                    style={{
                      textAlign: "center",
                      borderRadius: "5px",
                      boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
                    }}
                  >
                    <h3
                      style={{
                        fontSize: "16px",
                        fontWeight: "bold",
                        marginBottom: "10px",
                        color: "#333",
                      }}
                    >
                      {logoType.replace("_", " ").replace(/\b\w/g, (l) => l.toUpperCase())}
                    </h3>
                    <Upload
                      listType="picture-card"
                      beforeUpload={() => false}
                      fileList={files[logoType] ? [{ uid: "-1", name: files[logoType].name, status: "done" }] : []}
                      onChange={(info) => handleFileChange(logoType, info)}
                      maxCount={1}
                      style={{ marginBottom: "10px" }}
                    >
                      <div
                        style={{
                          display: "flex",
                          flexDirection: "column",
                          alignItems: "center",
                        }}
                      >
                        <UploadOutlined style={{ fontSize: "24px", color: "#666" }} />
                        <div style={{ marginTop: "8px", color: "#666" }}>Select File</div>
                      </div>
                    </Upload>
                    <p style={{ color: "#666", marginBottom: "10px", wordBreak: "break-all" }}>
                      {files[logoType] ? files[logoType].name : "No file selected"}
                    </p>
                    <div
                      style={{
                        backgroundColor: "#d4edda",
                        color: "#155724",
                        padding: "5px 10px",
                        borderRadius: "10px",
                        display: "inline-block",
                        marginBottom: "10px",
                        marginRight: "25px",
                      }}
                    >
                      ({logoDimensions[logoType].width}px X {logoDimensions[logoType].height}px)
                    </div>
                    <Button
                      type="default"
                      onClick={() => handleSubmit(logoType)}
                      disabled={isLoading || !files[logoType]}
                      style={{ backgroundColor: "#2094fc", color: "#fff", border: "none" }}
                    >
                      Update
                    </Button>
                  </Card>
                </Col>
              ))}
            </Row>
          </Col>

          {/* Right Side: Last Two Logos under "Login Page Background" title */}
          <Col xs={24} md={12}>
            <h2
              style={{
                fontSize: "20px",
                fontWeight: "bold",
                marginBottom: "15px",
                color: "#333",
                textAlign: "center",
              }}
            >
              Login Page Background
            </h2>
            <Row gutter={[16, 16]}>
              {lastTwoLogos.map((logoType) => (
                <Col xs={24} sm={24} md={24} key={logoType}>
                  <Card
                    bordered
                    style={{
                      textAlign: "center",
                      borderRadius: "5px",
                      boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
                    }}
                  >
                    <h3
                      style={{
                        fontSize: "16px",
                        fontWeight: "bold",
                        marginBottom: "10px",
                        color: "#333",
                      }}
                    >
                      {logoType
                        .replace("_", " ")
                        .replace(/\b\w/g, (l) => l.toUpperCase())
                        .replace("Page Background", "")}
                    </h3>
                    <Upload
                      listType="picture-card"
                      beforeUpload={() => false}
                      fileList={files[logoType] ? [{ uid: "-1", name: files[logoType].name, status: "done" }] : []}
                      onChange={(info) => handleFileChange(logoType, info)}
                      maxCount={1}
                      style={{ marginBottom: "10px" }}
                    >
                      <div
                        style={{
                          display: "flex",
                          flexDirection: "column",
                          alignItems: "center",
                        }}
                      >
                        <UploadOutlined style={{ fontSize: "24px", color: "#666" }} />
                        <div style={{ marginTop: "8px", color: "#666" }}>Select File</div>
                      </div>
                    </Upload>
                    <p style={{ color: "#666", marginBottom: "10px", wordBreak: "break-all" }}>
                      {files[logoType] ? files[logoType].name : "No file selected"}
                    </p>
                    <div
                      style={{
                        backgroundColor: "#d4edda",
                        color: "#155724",
                        padding: "5px 10px",
                        borderRadius: "10px",
                        display: "inline-block",
                        marginBottom: "10px",
                        marginRight: "25px",
                      }}
                    >
                      ({logoDimensions[logoType].width}px X {logoDimensions[logoType].height}px)
                    </div>
                    <Button
                      type="default"
                      onClick={() => handleSubmit(logoType)}
                      disabled={isLoading || !files[logoType]}
                      style={{ backgroundColor: "#2094fc", color: "#fff", border: "none" }}
                    >
                      Update
                    </Button>
                  </Card>
                </Col>
              ))}
            </Row>
          </Col>
        </Row>
      {error && (
        <p
          style={{
            color: "red",
            marginTop: "20px",
            textAlign: "center",
          }}
        >
          Error: {error.data?.error || "Upload failed"}
        </p>
      )}
      {data && (
        <div style={{ textAlign: "center", marginTop: "20px" }}>
          <p style={{ color: "#333", marginBottom: "10px" }}>{data.message}</p>
          <img
            src={data.url}
            alt="Uploaded Logo"
            style={{
              maxWidth: "200px",
              borderRadius: "4px",
              display: "block",
              margin: "0 auto",
            }}
          />
        </div>
      )}
    </div>
    </>
  );
};

export default LogoUpload;