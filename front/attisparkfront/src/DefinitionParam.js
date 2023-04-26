import React from 'react';
import './DefinitionParam.css';
import logoattineos from './logoattineos.png';
import {View,  TextInput} from 'react-native';




export default class DefinitionParam extends React.Component{
    state={
        domainNames: [],
        ips: [],
        bannedIps: [],
        contactEmail: "",
        
    };

    change = e=>{
      const { name, value } = e.target;
      if (name === "ips" || name === "bannedIps" || name=="domainNames") {
        const arr = value.split(",").map(item => item.trim());
        this.setState({ [name]: arr });
      } else {
        this.setState({ [name]: value });
      }
    };
    

    onSubmit= async (e) =>{
        e.preventDefault();
        console.log(this.state);

    const payload = {
       
        domainNames: this.state.domainNames,
        ips: this.state.ips,
        bannedIps: this.state.bannedIps,
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
          alert("Enregistrement fait avec succés.");
        } else {
          console.error("Erreur d'enregistrement.");
          alert("Erreur d'enregistrement.");
        }
      } catch (error) {
        console.error(error);
        alert("Une erreur s'est produite lors de l'enregistrement.");
      }
    };

    render(){
        return (
        <div>
           <header className = "bar-1"><img src={logoattineos} className='logo'/></header>
            <div>
            <div className="container">
                <h1 className="text-4xl font-bold mb-8">Définition du périmètre</h1>
                <form onSubmit={this.onSubmit}>
                    {/*domainNames Field*/}
                    <div className="mb-4">
                        <label htmlFor="domainNames" className="block font-bold mb-2">Nom du domaine</label>
                        <input
                        type="text"
                        id="domainNames"
                        name="domainNames"
                        className="border border-gray-400 p-2 w-full"
                        placeholder="Nom du domaine" 
                        value={this.state.domainNames} 
                        onChange={(e)  => this.change(e)}
                        wrap="hard"

                        />
                </div>
               
                {/* ips Field*/}
                <div className="mb-4">
                    <label htmlFor="ips" className="block font-bold mb-2">Adresses IP</label>
                    <input 
                    type="text"
                    id="ips"
                    name="ips"
                    className="border border-gray-400 p-2 w-full"
                    placeholder="Adresses IP" 
                    value={this.state.ips} 
                    onChange={(e)  => this.change(e) }
                    />
                </div>
                  {/* Adresse IP à exclure Field*/}
                  <div className="mb-4">
                    <label htmlFor="bannedIps" className="block font-bold mb-2">Adresse IP à exclure</label>
                    <input 
                    type="text"
                    id="bannedIps"
                    name="bannedIps"
                    className="border border-gray-400 p-2 w-full"
                    placeholder="Adresse IP à exclure " 
                    value={this.state.bannedIps} 
                    onChange={(e)  => this.change(e) }
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
                    />
                </div>
                
                {/* Submit button */}
                <div className="mb-4">
                <button type="submit" onClick={(e) => this.onSubmit(e)}>Enregsitrer</button>
                </div>
            </form>
        </div>
    </div>
    </div>
        );
    }





}
