import React, { useState } from "react";
import { useUpdateThemeMutation } from "../../redux/rtk/features/BackendTheme/BackendThemeApi"; // Adjust path
import PageTitle from "../page-header/PageHeader"; // Adjust path
import { Button, Card, Col, Row } from "antd";
import { toastHandler } from "../../utils/functions"; // Adjust path
import whiteTheme from "../../assets/themes/white.jpg"; // Placeholder, replace with actual path
import defaultTheme from "../../assets/themes/default.jpg"; // Placeholder
import redTheme from "../../assets/themes/red.jpg"; // Placeholder
import blueTheme from "../../assets/themes/blue.jpg"; // Placeholder
import grayTheme from "../../assets/themes/gray.jpg"; // Placeholder

const themeOptions = [
  { name: "white.jpg", label: "White", image: whiteTheme },
  { name: "default.jpg", label: "Default", image: defaultTheme },
  { name: "red.jpg", label: "Red", image: redTheme },
  { name: "blue.jpg", label: "Blue", image: blueTheme },
  { name: "gray.jpg", label: "Gray", image: grayTheme },
];

const BackendTheme = () => {
  const [selectedTheme, setSelectedTheme] = useState(null);
  const [updateTheme, { isLoading, error, data }] = useUpdateThemeMutation();

  // Handle theme selection
  const handleThemeSelect = (themeName) => {
    setSelectedTheme(themeName);
  };

  // Handle form submission
  const handleSubmit = async () => {
    if (!selectedTheme) {
      toastHandler("Please select a theme!", "warning");
      return;
    }

    const formData = new FormData();
    formData.append("theme", selectedTheme);

    try {
      const resp = await updateTheme(formData).unwrap();
      if (resp.data && !resp.error) {
        toastHandler("Theme updated successfully", "success");
      }
    } catch (err) {
      toastHandler("Failed to update theme, please try again", "warning");
    }
  };

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
        Backend Theme
      </h1>
      <Row gutter={[16, 16]} style={{ width: "100%", maxWidth: "1200px" }}>
        {themeOptions.map((theme) => (
          <Col xs={24} sm={12} md={8} key={theme.name}>
            <Card
              bordered
              style={{
                textAlign: "center",
                borderRadius: "5px",
                boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
                cursor: "pointer",
                border: selectedTheme === theme.name ? "2px solid #2894fc" : "1px solid #ddd", // Highlight selected theme
              }}
              onClick={() => handleThemeSelect(theme.name)}
            >
              <img
                src={theme.image}
                alt={`${theme.label} Preview`}
                style={{
                  width: "100%",
                  height: "150px",
                  objectFit: "cover",
                  borderRadius: "5px 5px 0 0",
                }}
              />
              <div
                style={{
                  backgroundColor: "#6c757d",
                  color: "#fff",
                  padding: "5px",
                  borderRadius: "0 0 5px 5px",
                }}
              >
                {theme.label}
              </div>
            </Card>
          </Col>
        ))}
      </Row>
      <Button
        type="default"
        onClick={handleSubmit}
        disabled={isLoading || !selectedTheme}
        style={{
          backgroundColor: "#2894fc", // Updated button color
          color: "#fff",
          border: "none",
          marginTop: "35px",
          padding: "20px 30px",
          fontSize: "16px",
          borderRadius: "5px",
          display: "flex", // Ensure text is centered
          justifyContent: "center", // Center text horizontally
          alignItems: "center", // Center text vertically
          width: "100px", // Fixed width for better centering
          margin: "0 auto", // Center the button
        }}
      >
        {isLoading ? "Saving..." : "Save"}
      </Button>
      {error && (
        <p
          style={{
            color: "red",
            marginTop: "20px",
            textAlign: "center",
          }}
        >
          Error: {error.data?.error || "Update failed"}
        </p>
      )}
      {data && (
        <div style={{ textAlign: "center", marginTop: "20px" }}>
          <p style={{ color: "#333", marginBottom: "10px" }}>{data.message}</p>
        </div>
      )}
    </div>
    </>
  );
};

export default BackendTheme;