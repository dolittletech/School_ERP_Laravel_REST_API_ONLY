import React, { useState } from 'react';

const EmailSetting = () => {
  // State to manage the selected email engine
  const [selectedEngine, setSelectedEngine] = useState('SendMail');
  const [formData, setFormData] = useState({
    engine: 'SendMail',
  });

  // Handle dropdown change
  const handleEngineChange = (e) => {
    const value = e.target.value;
    setSelectedEngine(value);
    setFormData((prevData) => ({
      ...prevData,
      engine: value,
    }));
  };

  // Handle form submission
  const handleSave = () => {
    console.log('Form Data Saved:', formData);
    // Add your save logic here (e.g., API call)
  };

  return (
    <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
      <div
        style={{
          border: '1px solid #ccc',
          borderRadius: '4px',
          padding: '20px',
          boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
          marginBottom: '20px',
        }}
      >
        <h2>Email Setting</h2>
      </div>

      <div
        style={{
          border: '1px solid #ccc',
          borderRadius: '4px',
          padding: '20px',
          boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
        }}
      >
        <div>
          <label>Email Engine</label>
          <select
            value={selectedEngine}
            onChange={handleEngineChange}
            style={{
              width: '100%',
              padding: '8px',
              marginBottom: '10px',
              border: '1px solid #ccc',
              borderRadius: '4px',
              fontSize: '14px',
            }}
          >
            <option value="SendMail" style={{ backgroundColor: selectedEngine === 'SendMail' ? '#007bff' : 'transparent', color: selectedEngine === 'SendMail' ? 'white' : 'black' }}>
              SendMail
            </option>
            <option value="SMTP" style={{ backgroundColor: selectedEngine === 'SMTP' ? '#007bff' : 'transparent', color: selectedEngine === 'SMTP' ? 'white' : 'black' }}>
              SMTP
            </option>
            <option value="AWS SES" style={{ backgroundColor: selectedEngine === 'AWS SES' ? '#007bff' : 'transparent', color: selectedEngine === 'AWS SES' ? 'white' : 'black' }}>
              AWS SES
            </option>
          </select>
        </div>
        <button
          onClick={handleSave}
          style={{
            backgroundColor: '#2094fc',
            color: 'white',
            padding: '8px 16px',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer',
            marginTop: '10px',
          }}
        >
          Save
        </button>
      </div>
    </div>
  );
};

export default EmailSetting;