import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import logoattineos from './logoattineos.png';
import './DefinitionParam.css';
function EntityTable() {
    const tableHeaderStyle = {
        backgroundColor: '#f2f2f2',
        padding: '8px',
        textAlign: 'left',
        fontWeight: 'bold',
      };
      
      // Define table data style
      const tableDataStyle = {
        padding: '8px',
      };
  const [perimeter, setPerimeter] = useState([]);

  useEffect(() => {
    // Fetch perimeters data from API endpoint
    fetch('https://127.0.0.1:8001/perimeter')
      .then(response => response.json())
      .then(data => {
        setPerimeter(data.items);
      })
      .catch(error => {
        console.error('Error fetching perimeter:', error);
      });
  },[]);

  return (
    <div>
      <header className = "bar-1"><img src={logoattineos} className='logo'  alt=""/></header>
        <div>
    <table style={{ width: '100%', borderCollapse: 'collapse' }}>
      <thead>
        <tr>
          <th  style={tableHeaderStyle}>ID</th>
          <th style={tableHeaderStyle} >Domaines</th>
          <th style={tableHeaderStyle}>ips</th>
          <th style={tableHeaderStyle}>bannedIps</th>
          <th style={tableHeaderStyle}>contact_mail</th>
          <th style={tableHeaderStyle}>created_at</th>
        </tr>
      </thead>
      <tbody>
        {perimeter.length ? (
          perimeter.map(item => (
            <tr key={item.id}>
              <td style={tableDataStyle}>{item.id}</td>
              <td style={tableDataStyle}> {item.domains.join(', ')}</td>
              <td style={tableDataStyle}>{item.ips.join(', ')}</td>
              <td style={tableDataStyle}>{item.bannedIps.join(', ')}</td>
              <td style={tableDataStyle}>{item.contact_mail}</td>
              <td style={tableDataStyle}>{item.created_at}</td>
              <td style={tableDataStyle}><Link to={`/modification-perimetre/${item.id}`}><button >Modifier</button></Link></td>
              
              

            </tr>
          ))
        ) : (
          <tr>
            <td colSpan="2" style={tableDataStyle}>Loading...</td>
          </tr>
        )}
      </tbody>
    </table>
    </div>
    </div>
  );
}

export default EntityTable;