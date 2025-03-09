import React, { useState } from 'react';

const SMSSetting = () => {
  // State to manage the selected gateway and its form data
  const [selectedGateway, setSelectedGateway] = useState('CustomSMSGateway');
  const [formData, setFormData] = useState({
    ClickatellSmsGateway: {
      username: '',
      password: '',
      apiKey: '',
      status: '',
    },
    TwilioSMSGateway: {
      accountSid: '',
      authToken: '',
      phoneNumber: '',
      status: '',
    },
    MSG91: {
      authKey: '',
      senderId: '',
      status: '',
    },
    TextLocal: {
      username: '',
      hashkey: '',
      senderId: '',
      status: '',
    },
    SMSCountry: {
      username: '',
      authKey: '',
      authToken: '',
      senderId: '',
      password: '',
      status: '',
    },
    BulkSMS: {
      username: '',
      password: '',
      status: '',
    },
    Mobireach: {
      authKey: '',
      senderId: '',
      routeId: '',
      status: '',
    },
    Nexmo: {
      apiKey: '',
      apiSecret: '',
      fromNumber: '',
      status: '',
    },
    AfricasTalking: {
      username: '',
      apiKey: '',
      shortCode: '',
      status: '',
    },
    SMSEgypt: {
      username: '',
      password: '',
      senderId: '',
      type: '',
      status: '',
    },
    CustomSMSGateway: {
      gatewayName: '',
      status: '',
    },
  });

  // Handle input changes
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [selectedGateway]: {
        ...prevData[selectedGateway],
        [name]: value,
      },
    }));
  };

  // Handle form submission
  const handleSave = () => {
    console.log('Form Data Saved:', formData[selectedGateway]);
    // Add your save logic here (e.g., API call)
  };

  // Gateway selection handler
  const handleGatewayChange = (gateway) => {
    setSelectedGateway(gateway);
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
        <p style={{fontSize: '20px'}}>SMS Setting</p>
        <div style={{ display: 'flex', gap: '20px', marginBottom: '20px', borderBottom: '2px solid #ddd', paddingBottom: '10px' }}>
          {[
            'ClickatellSmsGateway',
            'TwilioSMSGateway',
            'MSG91',
            'TextLocal',
            'SMSCountry',
            'BulkSMS',
            'Mobireach',
            'Nexmo',
            'AfricasTalking',
            'SMSEgypt',
            'CustomSMSGateway',
          ].map((gateway) => (
            <span
              key={gateway}
              onClick={() => handleGatewayChange(gateway)}
              style={{
                padding: '5px 15px',
                cursor: 'pointer',
                borderBottom: selectedGateway === gateway ? '3px solid #f5a623' : 'none',
                fontWeight: selectedGateway === gateway ? 'bold' : 'normal',
                color: selectedGateway === gateway ? '#333' : '#666',
                transition: 'all 0.3s ease',
              }}
            >
              {gateway.replace(/([A-Z])/g, ' $1').trim()}
            </span>
          ))}
        </div>
      

      <div style={{ display: 'flex', justifyContent: 'space-between' }}>
        <div
          style={{
            width: '50%',
            border: '1px solid #ccc',
            borderRadius: '4px',
            padding: '20px',
            boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
          }}
        >
          <h3>Custom SMS Gateway</h3>
          {selectedGateway === 'CustomSMSGateway' && (
            <div>
              <div>
                <label>Gateway Name *</label>
                <input
                  type="text"
                  name="gatewayName"
                  value={formData.CustomSMSGateway.gatewayName}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.CustomSMSGateway.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.CustomSMSGateway.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.CustomSMSGateway.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.CustomSMSGateway.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.CustomSMSGateway.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'ClickatellSmsGateway' && (
            <div>
              <div>
                <label>Clickatell Username *</label>
                <input
                  type="text"
                  name="username"
                  value={formData.ClickatellSmsGateway.username}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Clickatell Password *</label>
                <input
                  type="password"
                  name="password"
                  value={formData.ClickatellSmsGateway.password}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Clickatell Api Key *</label>
                <input
                  type="text"
                  name="apiKey"
                  value={formData.ClickatellSmsGateway.apiKey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.ClickatellSmsGateway.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.ClickatellSmsGateway.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.ClickatellSmsGateway.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.ClickatellSmsGateway.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.ClickatellSmsGateway.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'TwilioSMSGateway' && (
            <div>
              <div>
                <label>Twilio Account SID *</label>
                <input
                  type="text"
                  name="accountSid"
                  value={formData.TwilioSMSGateway.accountSid}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Authentication Token *</label>
                <input
                  type="text"
                  name="authToken"
                  value={formData.TwilioSMSGateway.authToken}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Registered Phone Number *</label>
                <input
                  type="text"
                  name="phoneNumber"
                  value={formData.TwilioSMSGateway.phoneNumber}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.TwilioSMSGateway.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.TwilioSMSGateway.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.TwilioSMSGateway.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.TwilioSMSGateway.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.TwilioSMSGateway.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'MSG91' && (
            <div>
              <div>
                <label>Auth Key *</label>
                <input
                  type="text"
                  name="authKey"
                  value={formData.MSG91.authKey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Sender ID *</label>
                <input
                  type="text"
                  name="senderId"
                  value={formData.MSG91.senderId}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.MSG91.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.MSG91.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.MSG91.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.MSG91.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.MSG91.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'TextLocal' && (
            <div>
              <div>
                <label>Username *</label>
                <input
                  type="text"
                  name="username"
                  value={formData.TextLocal.username}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Hashkey *</label>
                <input
                  type="text"
                  name="hashkey"
                  value={formData.TextLocal.hashkey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Sender ID *</label>
                <input
                  type="text"
                  name="senderId"
                  value={formData.TextLocal.senderId}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.TextLocal.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.TextLocal.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.TextLocal.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.TextLocal.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.TextLocal.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'SMSCountry' && (
            <div>
              <div>
                <label>Username *</label>
                <input
                  type="text"
                  name="username"
                  value={formData.SMSCountry.username}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Auth Key *</label>
                <input
                  type="text"
                  name="authKey"
                  value={formData.SMSCountry.authKey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Authentication Token *</label>
                <input
                  type="text"
                  name="authToken"
                  value={formData.SMSCountry.authToken}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Sender ID *</label>
                <input
                  type="text"
                  name="senderId"
                  value={formData.SMSCountry.senderId}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Password *</label>
                <input
                  type="password"
                  name="password"
                  value={formData.SMSCountry.password}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.SMSCountry.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.SMSCountry.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.SMSCountry.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.SMSCountry.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.SMSCountry.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'BulkSMS' && (
            <div>
              <div>
                <label>Username *</label>
                <input
                  type="text"
                  name="username"
                  value={formData.BulkSMS.username}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Password *</label>
                <input
                  type="password"
                  name="password"
                  value={formData.BulkSMS.password}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.BulkSMS.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.BulkSMS.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.BulkSMS.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.BulkSMS.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.BulkSMS.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'Mobireach' && (
            <div>
              <div>
                <label>Auth Key *</label>
                <input
                  type="text"
                  name="authKey"
                  value={formData.Mobireach.authKey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Sender ID *</label>
                <input
                  type="text"
                  name="senderId"
                  value={formData.Mobireach.senderId}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Route ID *</label>
                <input
                  type="text"
                  name="routeId"
                  value={formData.Mobireach.routeId}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.Mobireach.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.Mobireach.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.Mobireach.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.Mobireach.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.Mobireach.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'Nexmo' && (
            <div>
              <div>
                <label>Nexmo Api Key *</label>
                <input
                  type="text"
                  name="apiKey"
                  value={formData.Nexmo.apiKey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Nexmo Api Secret *</label>
                <input
                  type="text"
                  name="apiSecret"
                  value={formData.Nexmo.apiSecret}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Registered / From Number *</label>
                <input
                  type="text"
                  name="fromNumber"
                  value={formData.Nexmo.fromNumber}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.Nexmo.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.Nexmo.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.Nexmo.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.Nexmo.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.Nexmo.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'AfricasTalking' && (
            <div>
              <div>
                <label>Username *</label>
                <input
                  type="text"
                  name="username"
                  value={formData.AfricasTalking.username}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Api Key *</label>
                <input
                  type="text"
                  name="apiKey"
                  value={formData.AfricasTalking.apiKey}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Short Code *</label>
                <input
                  type="text"
                  name="shortCode"
                  value={formData.AfricasTalking.shortCode}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.AfricasTalking.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.AfricasTalking.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.AfricasTalking.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.AfricasTalking.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.AfricasTalking.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
          {selectedGateway === 'SMSEgypt' && (
            <div>
              <div>
                <label>Username *</label>
                <input
                  type="text"
                  name="username"
                  value={formData.SMSEgypt.username}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Password *</label>
                <input
                  type="password"
                  name="password"
                  value={formData.SMSEgypt.password}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Sender ID *</label>
                <input
                  type="text"
                  name="senderId"
                  value={formData.SMSEgypt.senderId}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                />
              </div>
              <div>
                <label>Type *</label>
                <select
                  name="type"
                  value={formData.SMSEgypt.type}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option value="Type1">Type1</option>
                  <option value="Type2">Type2</option>
                </select>
              </div>
              <div>
                <label>Status *</label>
                <select
                  name="status"
                  value={formData.SMSEgypt.status}
                  onChange={handleInputChange}
                  style={{ width: '100%', padding: '8px', marginBottom: '10px', border: '1px solid #ccc', borderRadius: '4px' }}
                >
                  <option value="">Select</option>
                  <option
                    value="Enabled"
                    style={{
                      backgroundColor: formData.SMSEgypt.status === 'Enabled' ? '#2094fc' : 'transparent',
                      color: formData.SMSEgypt.status === 'Enabled' ? 'white' : 'black',
                    }}
                  >
                    Enabled
                  </option>
                  <option
                    value="Disabled"
                    style={{
                      backgroundColor: formData.SMSEgypt.status === 'Disabled' ? '#ccc' : 'transparent',
                      color: formData.SMSEgypt.status === 'Disabled' ? 'black' : 'black',
                    }}
                  >
                    Disabled
                  </option>
                </select>
              </div>
            </div>
          )}
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
        <div style={{ width: '40%', textAlign: 'center' }}>
          {selectedGateway === 'ClickatellSmsGateway' && (
            <div>
              <img src="https://via.placeholder.com/150?text=Clickatell+Logo" alt="Clickatell Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://www.clickatell.com" style={{ color: '#007bff' }}>https://www.clickatell.com</a></p>
            </div>
          )}
          {selectedGateway === 'TwilioSMSGateway' && (
            <div>
              <img src="https://via.placeholder.com/150?text=Twilio+Logo" alt="Twilio Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://www.twilio.com" style={{ color: '#007bff' }}>https://www.twilio.com</a></p>
            </div>
          )}
          {selectedGateway === 'MSG91' && (
            <div>
              <img src="https://via.placeholder.com/150?text=MSG91+Logo" alt="MSG91 Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://msg91.com" style={{ color: '#007bff' }}>https://msg91.com</a></p>
            </div>
          )}
          {selectedGateway === 'TextLocal' && (
            <div>
              <img src="https://via.placeholder.com/150?text=TextLocal+Logo" alt="TextLocal Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://www.textlocal.in" style={{ color: '#007bff' }}>https://www.textlocal.in</a></p>
            </div>
          )}
          {selectedGateway === 'SMSCountry' && (
            <div>
              <img src="https://via.placeholder.com/150?text=SMSCountry+Logo" alt="SMSCountry Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://www.smscountry.com" style={{ color: '#007bff' }}>https://www.smscountry.com</a></p>
            </div>
          )}
          {selectedGateway === 'BulkSMS' && (
            <div>
              <img src="https://via.placeholder.com/150?text=BulkSMS+Logo" alt="BulkSMS Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://www.bulksms.com" style={{ color: '#007bff' }}>https://www.bulksms.com</a></p>
            </div>
          )}
          {selectedGateway === 'Mobireach' && (
            <div>
              <img src="https://via.placeholder.com/150?text=Mobireach+Logo" alt="Mobireach Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://user.mobireach.com.bd" style={{ color: '#007bff' }}>https://user.mobireach.com.bd</a></p>
            </div>
          )}
          {selectedGateway === 'Nexmo' && (
            <div>
              <img src="https://via.placeholder.com/150?text=Nexmo+Logo" alt="Nexmo Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://dashboard.nexmo.com/sign-up" style={{ color: '#007bff' }}>https://dashboard.nexmo.com/sign-up</a></p>
            </div>
          )}
          {selectedGateway === 'AfricasTalking' && (
            <div>
              <img src="https://via.placeholder.com/150?text=AfricasTalking+Logo" alt="AfricasTalking Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://africastalking.com" style={{ color: '#007bff' }}>https://africastalking.com</a></p>
            </div>
          )}
          {selectedGateway === 'SMSEgypt' && (
            <div>
              <img src="https://via.placeholder.com/150?text=SMSEgypt+Logo" alt="SMSEgypt Logo" style={{ maxWidth: '150px' }} />
              <p><a href="https://smseg.com" style={{ color: '#007bff' }}>https://smseg.com</a></p>
            </div>
          )}
          {selectedGateway === 'CustomSMSGateway' && (
            <div>
              <img src="https://via.placeholder.com/150?text=Custom+SMS+Logo" alt="Custom SMS Logo" style={{ maxWidth: '150px' }} />
              <p><a href="#" style={{ color: '#007bff' }}>https://example.com</a></p>
            </div>
          )}
        </div>
      </div>
    </div>
    </div>
  );
};

export default SMSSetting;