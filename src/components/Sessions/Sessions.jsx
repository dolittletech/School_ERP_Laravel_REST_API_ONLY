import React, { useState } from 'react';

const SessionList = () => {
  // State for new session input
  const [newSession, setNewSession] = useState('');

  // State for session list (hardcoded for now)
  const [sessions, setSessions] = useState([
    { id: 1, session: '2016-17', status: '' },
    { id: 2, session: '2017-18', status: '' },
    { id: 3, session: '2018-19', status: '' },
    { id: 4, session: '2019-20', status: '' },
    { id: 5, session: '2020-21', status: '' },
    { id: 6, session: '2021-22', status: '' },
    { id: 7, session: '2022-23', status: '' },
    { id: 8, session: '2023-24', status: 'Active' },
    { id: 9, session: '2024-25', status: '' },
    { id: 10, session: '2025-26', status: '' },
    { id: 11, session: '2026-27', status: '' },
    { id: 12, session: '2027-28', status: '' },
    { id: 13, session: '2028-29', status: '' },
    { id: 14, session: '2029-30', status: '' },
  ]);

  // State for filters
  const [sessionFilter, setSessionFilter] = useState('');
  const [statusFilter, setStatusFilter] = useState('');

  // Handle adding new session
  const handleAddSession = () => {
    if (newSession.trim()) {
      const newSessionObj = {
        id: sessions.length + 1,
        session: newSession,
        status: '',
      };
      setSessions([...sessions, newSessionObj]);
      setNewSession('');
    }
  };

  // Filter sessions based on input
  const filteredSessions = sessions.filter((session) => {
    const matchesSession = session.session
      .toLowerCase()
      .includes(sessionFilter.toLowerCase());
    const matchesStatus = statusFilter
      ? session.status.toLowerCase() === statusFilter.toLowerCase()
      : true;
    return matchesSession && matchesStatus;
  });

  return (
    <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
        <div style={{ width: '30%' }}>
          <h3>Add Session</h3>
          <input
            type="text"
            value={newSession}
            onChange={(e) => setNewSession(e.target.value)}
            placeholder="Session *"
            style={{
              width: '100%',
              padding: '8px',
              marginBottom: '10px',
              border: '1px solid #ccc',
              borderRadius: '4px',
            }}
          />
          <button
            onClick={handleAddSession}
            style={{
              backgroundColor: '#2094fc',
              color: 'white',
              padding: '8px 16px',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
            }}
          >
            Save
          </button>
        </div>
        <div style={{ width: '60%' }}>
          <h3>Session List</h3>
          <div style={{ marginBottom: '10px' }}>
            <input
              type="text"
              placeholder="Search..."
              value={sessionFilter}
              onChange={(e) => setSessionFilter(e.target.value)}
              style={{
                padding: '8px',
                border: '1px solid #ccc',
                borderRadius: '4px',
                marginRight: '10px',
              }}
            />
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              style={{
                padding: '8px',
                border: '1px solid #ccc',
                borderRadius: '4px',
              }}
            >
              <option value="">Status ▼</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ backgroundColor: '#f5f5f5', borderBottom: '2px solid #ddd' }}>
                <th style={{ padding: '10px', textAlign: 'left' }}>Session</th>
                <th style={{ padding: '10px', textAlign: 'left' }}>Status</th>
                <th style={{ padding: '10px', textAlign: 'left' }}>Action</th>
              </tr>
            </thead>
            <tbody>
              {filteredSessions.map((session) => (
                <tr
                  key={session.id}
                  style={{
                    borderBottom: '1px solid #ddd',
                  }}
                >
                  <td style={{ padding: '10px' }}>{session.session}</td>
                  <td style={{ padding: '10px' }}>
                    {session.status === 'Active' && (
                      <span
                        style={{
                          backgroundColor: '#28a745',
                          color: 'white',
                          padding: '2px 8px',
                          borderRadius: '4px',
                        }}
                      >
                        Active
                      </span>
                    )}
                  </td>
                  <td style={{ padding: '10px' }}>
                    <span style={{ marginRight: '10px' }}>⋮</span>
                    <span style={{ color: 'red' }}>✖</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          <div style={{ marginTop: '10px', textAlign: 'right' }}>
            Records: 1 to {filteredSessions.length} of {sessions.length}
          </div>
        </div>
      </div>
    </div>
  );
};

export default SessionList;