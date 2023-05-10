import React from 'react';
import './DefinitionParam.css';
import logoattineos from './logoattineos.png';
import Dialog from '@mui/material/Dialog';
import DialogTitle from '@mui/material/DialogTitle';
import DialogContent from '@mui/material/DialogContent';
import DialogActions from '@mui/material/DialogActions';

export default class DefinitionParam extends React.Component{
  state = {
    domainNames: [],
    ips: [],
    bannedIps: [],
    contactEmail: '',
  };

  handleChange = (e) => {
    const { name, value } = e.target;
    this.setState({ [name]: value });
  };

  handleSubmit = (e) => {
    e.preventDefault();
    // Effectuez ici les actions nécessaires pour enregistrer les modifications
    // Utilisez this.state pour accéder aux valeurs des champs modifiés
    console.log(this.state);
  };

  render() {
    return (
      <div>
        <h1>Modification des paramètres du périmètre</h1>
        <form onSubmit={this.handleSubmit}>
          {/* Champ "Nom du domaine" */}
          <div>
            <label htmlFor="domainNames">Nom du domaine :</label>
            <input
              type="text"
              id="domainNames"
              name="domainNames"
              value={this.state.domainNames}
              onChange={this.handleChange}
            />
          </div>

          {/* Champ "Adresses IP" */}
          <div>
            <label htmlFor="ips">Adresses IP :</label>
            <textarea
              id="ips"
              name="ips"
              value={this.state.ips}
              onChange={this.handleChange}
            ></textarea>
          </div>

          {/* Champ "Adresses IP exclues" */}
          <div>
            <label htmlFor="bannedIps">Adresses IP exclues :</label>
            <textarea
              id="bannedIps"
              name="bannedIps"
              value={this.state.bannedIps}
              onChange={this.handleChange}
            ></textarea>
          </div>

          {/* Champ "Mail de contact" */}
          <div>
            <label htmlFor="contactEmail">Mail de contact :</label>
            <input
              type="text"
              id="contactEmail"
              name="contactEmail"
              value={this.state.contactEmail}
              onChange={this.handleChange}
            />
          </div>

          {/* Bouton de soumission */}
          <button type="submit">Enregistrer les modifications</button>
        </form>
      </div>
    );
  }
}

