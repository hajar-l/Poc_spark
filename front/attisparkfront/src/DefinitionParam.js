import React from 'react';
import './DefinitionParam.css';
import logoattineos from './logoattineos.png';
import Dialog from '@mui/material/Dialog';
import DialogTitle from '@mui/material/DialogTitle';
import DialogContent from '@mui/material/DialogContent';
import DialogActions from '@mui/material/DialogActions';
import { Link } from 'react-router-dom';
//import Perimeter from 'C:/Users/HajarLOULIDI/Attispark/Poc_spark/back/src/Entity/Perimeter.php';
export default class DefinitionParam extends React.Component{
    state={
        domainNames: [],
        ips: [],
        ipsString: "",
        bannedIps: [],
        bannedIpsString: "",
        contactEmail: "",
        confirmationMessage: "",
        ErrorMessage:"",
        review: false,
        successMessageDisplay: false,
        errorMessageDisplay: false,
        
    };

    change = e=>{
      const { name, value } = e.target;
      if (name==="domainNames") {
        const arr = value.split([","]).map(item => item.trim()) ;
        this.setState({ [name]: arr });
      } else {
        this.setState({ [name]: value });
      }
    };

    changeIp = e=>{
      const { name, value } = e.target;
      if (name==="ipsString") {
          this.setState({'ipsString': value});
      } else if ((name==="bannedIpsString")){
          this.setState({'bannedIpsString': value});
      }
  };

    handleKeyPress = (event, name) => {
      if (event.key === 'Enter' || event.key === ' ') {
          const fieldValue = this.state[name];
          this.setState({ [name]: fieldValue + '\n' });
          event.preventDefault();
      }
  };
    

    onSubmit= async (e) =>{
        e.preventDefault();
        let arr = this.state.ipsString.split(["\n"]).map(item => item.trim()) ;
        arr = arr.filter(function(element) {
            return element !== "";
        });

        this.setState({ ips: arr });
        let arr2 = this.state.bannedIpsString.split(["\n"]).map(item => item.trim()) ;
        arr2 = arr2.filter(function(element) {
            return element !== "";
        });
        this.setState({ bannedIps: arr2 });

        console.log(this.state);

    const payload = {
       
        domainNames: this.state.domainNames,
        ips: arr,
        bannedIps: arr2,
        contactEmail: this.state.contactEmail,
        
      };

      console.log('request payload',payload);

      try {
        const response = await fetch('https://127.0.0.1:8001/perimeter', {
          method: 'POST',
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
    handleReviewClick = () => {
      this.setState({ review: true });
    };
  
    handleReviewClose = () => {
      this.setState({ review: false });
    };
    handleButtonClick = (Perimeter) => {
      // Access the id attribute of the perimeter object
      const perimeterId = Perimeter.id;
    };
    render(){
        return (
        <div>
           <header className = "bar-1"><img src={logoattineos} className='logo'  alt=""/></header>
            <div>
            <div className="container">
                <h1 className="text-4xl font-bold mb-8">Définition du périmètre</h1>
                <form onSubmit={this.onSubmit}>
                    {/*domainNames Field*/}
                    <div className="mb-4">
                        <label htmlFor="domainNames" className="block font-bold mb-2">Nom du domaine</label>
                        <input  
                        //error={this.state.domainNames.length === 0}
                        //helperText={!this.state.domainNames.length ? 'domain name is required' : ''}
                        type="text"
                        id="domainNames"
                        name="domainNames"
                        className="border border-gray-400 p-2 w-full"
                        placeholder="Nom du domaine" 
                        value={this.state.domainNames} 
                        onChange={(e)  => this.change(e)}
                        required

                        />
                </div>
               
                {/* ips Field*/}
                <div className="mb-4">
                    <label htmlFor="ips" className="block font-bold mb-2">Adresses IP</label>
                    <textarea 
                    rows={10}
                    id="ipsString"
                    name="ipsString"
                    className="border border-gray-400 p-2 w-full"
                    placeholder="Adresses IP" 
                    value={this.state.ipsString} 
                    onChange={(e)  => this.change(e) }
                    onKeyPress={(event) => this.handleKeyPress(event, 'ipsString')}
                    required
                    />
                </div>
                  {/* Adresse IP à exclure Field*/}
                  <div className="mb-4">
                    <label htmlFor="bannedIps" className="block font-bold mb-2">Adresse IP à exclure</label>
                    <textarea 
                    
                    id="bannedIpsString"
                    name="bannedIpsString"
                    className="border border-gray-400 p-2 w-full"
                    placeholder="Adresse IP à exclure " 
                    value={this.state.bannedIpsString} 
                    onChange={(e)  => this.change(e) }
                    onKeyPress={(event) => this.handleKeyPress(event, 'bannedIpsString')}
                    required
                    
                    />
                </div>

                {/* Mail de contact Field*/}
                <div className="mb-4">
                    <label htmlFor="contactEmail" className="block font-bold mb-2">Mail de contact</label>
                    <input 
                    type="text"
                    id="contactEmail"
                    name="contactEmail"
                    className="border border-gray-400 p-2 w-full"
                    placeholder="Mail de contact" 
                    value={this.state.contactEmail} 
                    onChange={(e)  => this.change(e) }
                    required
                    />
                </div>

                <button variant="contained" onMouseDown={this.handleReviewClick}>Vérifier</button>
                <Link to={"/EntityDisplay"}><button >Modifier un périmètre</button></Link>
                <Dialog open={this.state.review} onClose={this.handleReviewClose}>
                <DialogTitle>Veuillez vérifier vos informations avant de soumettre</DialogTitle>
                <DialogContent>
                  <ul>
                  <li>
                  <span className="font-bold">Nom du domaine:</span> {this.state.domainNames.join(', ')}
                  
                  </li>
                  <li>
                  <span className="font-bold">Adresses IP:</span> {this.state.ipsString.replaceAll("\n", ",").replaceAll(",,", ",")}
                  
                  </li>
                  <li>
                  <span className="font-bold">Adresses IP exclues:</span> {this.state.bannedIpsString.replaceAll("\n", ",")}
                  
                  </li>
                  <li>
                  <span className="font-bold">Email de contact:</span> {this.state.contactEmail}
                  
                  </li>
                  </ul>
                </DialogContent>
                <DialogActions><button onClick={this.handleReviewClose}>Retour</button>
                               <button type="submit" variant="contained" onClick={(e) => this.onSubmit(e)}>Soumettre</button>
      
                </DialogActions>
                {this.state.successMessageDisplay && <p className="success-message">{this.state.confirmationMessage}</p>}
                {this.state.errorMessageDisplay && <p className="error-message">{this.state.ErrorMessage}</p>}
                </Dialog>
                {/* Submit button */}
                
                
            </form>
        </div>
    </div>
    </div>
        );
    }





}

