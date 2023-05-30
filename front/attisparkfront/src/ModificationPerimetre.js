import React, { useEffect, useState } from 'react';
import { useParams ,useNavigate  } from 'react-router-dom';
import logoattineos from './logoattineos.png';
import './DefinitionParam.css';
import { Link } from 'react-router-dom';

function ModificationPerimetre() {
  const [domains,setDomains]=useState([]);
  const [ips,setIps]=useState([]);
  const [bannedIps,setBannedIps]=useState([]);
  const [contact_mail,setContactMail]=useState('');

  const { id } = useParams();
  const navigate = useNavigate ();
  
  useEffect(() => {
    // Fetch perimeter data based on the ID
    fetch(`https://127.0.0.1:8001/perimeter/${id}`)
      .then(response => response.json())
      .then(data => {
        setDomains(data.domains || []);
        setIps(data.ips || []);
        setBannedIps(data.bannedIps || []);
        setContactMail(data.contact_mail || '');
      })
      .catch(error => {
        console.error('Error fetching perimeter:', error);
      });
  }, [id]);

  // Handle form submission
  const handleSubmit = e => {
    e.preventDefault();
    const payload ={
      domains,
      ips,
      bannedIps,
      contact_mail: contact_mail,
    }
    console.log("the payload is:", payload);
    fetch(`https://127.0.0.1:8001/perimeter/${id}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    })
      .then(response => response.json())
      .then(data => {
        // Handle the response or perform any additional actions
        console.log('Perimeter updated:', data);
        // Redirect to a success page or navigate back to the previous page
        navigate(-1)
      })
      .catch(error => {
        console.error('Error updating perimeter:', error);
        // Handle the error or display an error message
      });
  };
 
 
  return (
    <div>
      <header className = "bar-1"><img src={logoattineos} className='logo'  alt=""/></header>
        <div>
          <div className="container">
            <h1 className="text-4xl font-bold mb-8">Modification du périmètre</h1>
            <form onSubmit={handleSubmit}>
            <div>
                <label htmlFor="domains">Nom de domaine:</label>
                <input type="text" id="domains" name="domains" value={domains} onChange={e =>setDomains(e.target.value)}/>
            </div>
            <div>
                <label htmlFor="ips">Adresses IP:</label>
                <textarea type="ips" id="ips" name="ips" value={ips} onChange={e =>setIps(e.target.value)}/>
            </div>
            <div>
                <label htmlFor="bannedIps">Adresses IP à exclure:</label>
                <textarea type="bannedIps" id="bannedIps" name="bannedIps" value={bannedIps} onChange={e =>setBannedIps(e.target.value)}/>
            </div>
            <div className="mb-4">
              <label htmlFor="contact_mail">Contact Email:</label>
              <input type="text" id="contact_mail" name="contact_mail"  value={contact_mail} onChange={e =>setContactMail(e.target.value)}/>
            </div>
              
           <button type="submit">Enregistrer les modifications</button>
            </form>
          </div>
        </div>
      </div>
  );
}

export default ModificationPerimetre; 