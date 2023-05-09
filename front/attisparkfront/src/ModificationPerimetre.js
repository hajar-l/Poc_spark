import React from 'react';

export default class ModificationPerimetre extends React.Component {
    constructor(props) {
      super(props);
      this.state = {
        domainNames: [],
        ips: [],
        bannedIps: [],
        contactEmail: "",
        confirmationMessage: "",
        ErrorMessage:"",
        review: false,
        successMessageDisplay: false,
        errorMessageDisplay: false,
        isDisabled: false,
      };
    }
  
    async componentDidMount() {
        try {
          const response = await fetch('https://127.0.0.1:8001/perimeter/${this.props.match.params.id}');
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          const data = await response.json();
          this.setState({
            domainNames: data.domainNames.join(", "),
            ips: data.ips.join(", "),
            bannedIps: data.bannedIps.join(", "),
            contactEmail: data.contactEmail,
          });
        } catch (error) {
          console.error(error);
          
        }
      }
  
    render() {
      return (
        <div>
          <h1>Modification d'un périmètre</h1>
          <form onSubmit={this.onSubmit}>
          <label htmlFor="domainNames">Noms de domaine :</label>
          <input type="text" name="domainNames" value={this.state.domainNames} onChange={this.change} />

          <label htmlFor="ips">Adresses IP :</label>
          <input type="text" name="ips" value={this.state.ips} onChange={this.change} />

          <label htmlFor="bannedIps">Adresses IP à exclure :</label>
          <input type="text" name="bannedIps" value={this.state.bannedIps} onChange={this.change} />

          <label htmlFor="contactEmail">Mail de contact :</label>
          <input type="email" name="contactEmail" value={this.state.contactEmail} onChange={this.change} />

          <button type="submit">Modifier</button>
        </form>
        {this.state.successMessageDisplay && <p>{this.state.confirmationMessage}</p>}
        {this.state.errorMessageDisplay && <p>{this.state.errorMessage}</p>}
        </div>
      );
    }
  
    change = e => {
      const { name, value } = e.target;
      if (name === "ips" || name === "bannedIps" || name==="domainNames") {
        const arr = value.split([","]).map(item => item.trim()) ;
        this.setState({ [name]: arr });
      } else {
        this.setState({ [name]: value });
      }
    };
  
    onSubmit = async e => {
      e.preventDefault();
      console.log(this.state);
  
      const payload = {
        domainNames: this.state.domainNames,
        ips: this.state.ips,
        bannedIps: this.state.bannedIps,
        contactEmail: this.state.contactEmail
      };
  
      console.log('request payload',payload);
  
      try {
        const response = await fetch('https://127.0.0.1:8001/perimeter/${this.props.match.params.id}', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(payload),
        });
  
        if (response.ok) {
          console.log('Enregistrement fait avec succés.');
          this.setState({ confirmationMessage: "Enregistrement fait avec succès.", successMessageDisplay: true });
          setTimeout(() => {
            this.setState({ successMessageDisplay: false });
          }, 5000);
        } else {
          console.error("Erreur d'enregistrement.");
          this.setState({ ErrorMessage: "Veuillez vérifier les informations saisies." ,errorMessageDisplay: true });
          setTimeout(() => {
            this.setState({ errorMessageDisplay: false });
          }, 5000); 
        } 
      } catch (error) {
        console.error(error);
        alert("Une erreur s'est produite lors de l'enregistrement.");
      }
    };
  }