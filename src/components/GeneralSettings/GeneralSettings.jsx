import React, { useState, useEffect } from "react";
import { Form, Input, Select, Button, Alert, Row, Col, Typography } from "antd";
import { useGetGeneralSettingsQuery, useUpdateGeneralSettingsMutation } from "../../redux/rtk/features/GeneralSettings/generalSettingApi"; // Adjust path
const { Title } = Typography;
const { Option } = Select;

const GeneralSettings = () => {
  const [formValues, setFormValues] = useState({
    school_name: "",
    school_code: "",
    address: "",
    phone: "",
    email: "",
    session_id: "2023-24",
    start_month: "January",
    date_format: "d/m/Y",
    timezone: "UTC",
    start_week: "Monday",
    currency_format: "$0.00",
    base_url: "",
    folder_path: "",
  });
  const [message, setMessage] = useState("");

  const { data: settings, isLoading, isError } = useGetGeneralSettingsQuery();
  const [updateGeneralSettings, { isLoading: isUpdating }] = useUpdateGeneralSettingsMutation();

  useEffect(() => {
    if (settings) {
      setFormValues(settings);
    }
  }, [settings]);

  const onFinish = async (values) => {
    try {
      await updateGeneralSettings(values).unwrap();
      setMessage("General Settings updated successfully");
    } catch (error) {
      setMessage(`Error updating settings: ${error.message || "Please try again"}`);
    }
  };

  const onFinishFailed = (errorInfo) => {
    setMessage("Please fill in all required fields");
  };

  if (isLoading) return <div>Loading...</div>;
  if (isError) return <div>Error fetching settings</div>;

  return (
    <div style={{ padding: "20px", background: "#fff", borderRadius: "5px" }}>
      <Title level={2} style={{ color: "#333" }}>
        General Setting
      </Title>
      {message && (
        <Alert
          message={message}
          type={message.includes("Error") ? "error" : "success"}
          showIcon
          style={{ marginBottom: "20px" }}
        />
      )}
      <Form
        layout="vertical"
        initialValues={formValues}
        onFinish={onFinish}
        onFinishFailed={onFinishFailed}
      >
        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>School Name *</span>}
              name="school_name"
              rules={[{ required: true, message: "Please input the school name!" }]}
            >
              <Input placeholder="Your School Name" />
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>School Code</span>}
              name="school_code"
            >
              <Input placeholder="Your School Code" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Address *</span>}
              name="address"
              rules={[{ required: true, message: "Please input the address!" }]}
            >
              <Input placeholder="Your School Address" />
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Phone *</span>}
              name="phone"
              rules={[{ required: true, message: "Please input the phone number!" }]}
            >
              <Input placeholder="Your School Phone" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Email *</span>}
              name="email"
              rules={[{ required: true, message: "Please input the email!", type: 'email' }]}
            >
              <Input placeholder="yourschool@email.domain.com" />
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Academic Session</span>}
              name="session_id"
            >
              <Input placeholder="2023-24" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Session Start Month *</span>}
              name="start_month"
              rules={[{ required: true, message: "Please select the start month!" }]}
            >
              <Select placeholder="Select Month">
                <Option value="April">April</Option>
                <Option value="January">January</Option>
                <Option value="July">July</Option>
                <Option value="September">September</Option>
              </Select>
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Date Format *</span>}
              name="date_format"
              rules={[{ required: true, message: "Please select the date format!" }]}
            >
              <Select placeholder="Select Format">
                <Option value="mm/dd/yyyy">mm/dd/yyyy</Option>
                <Option value="dd/mm/yyyy">dd/mm/yyyy</Option>
                <Option value="yyyy/mm/dd">yyyy/mm/dd</Option>
              </Select>
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Timezone *</span>}
              name="timezone"
              rules={[{ required: true, message: "Please select the timezone!" }]}
            >
              <Select placeholder="Select Timezone">
                <Option value="(GMT) UTC">(GMT) UTC</Option>
                <Option value="(GMT+1) CET">(GMT+1) CET</Option>
                <Option value="(GMT-5) EST">(GMT-5) EST</Option>
              </Select>
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Start Day of Week *</span>}
              name="start_week"
              rules={[{ required: true, message: "Please select the start day!" }]}
            >
              <Select placeholder="Select Day">
                <Option value="Monday">Monday</Option>
                <Option value="Sunday">Sunday</Option>
              </Select>
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Currency Format *</span>}
              name="currency_format"
              rules={[{ required: true, message: "Please select the currency format!" }]}
            >
              <Select placeholder="Select Format">
                <Option value="$12345678.00">$12345678.00</Option>
                <Option value="€12345678.00">€12345678.00</Option>
                <Option value="£12345678.00">£12345678.00</Option>
              </Select>
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>File Upload Path</span>}
              name="folder_path"
            >
              <Input placeholder="/applications/MAMP/htdocs/ss/" />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              label={<span style={{ color: "#333", fontWeight: 500 }}>Base URL *</span>}
              name="base_url"
              rules={[{ required: true, message: "Please input the base URL!" }]}
            >
              <Input placeholder="http://localhost:8888/ss/" />
            </Form.Item>
          </Col>
        </Row>

        <Form.Item>
          <Button
            type="primary"
            htmlType="submit"
            loading={isUpdating}
            style={{ background: "#1890ff", borderColor: "#1890ff" }}
          >
            Save
          </Button>
        </Form.Item>
      </Form>
    </div>
  );
};

export default GeneralSettings;