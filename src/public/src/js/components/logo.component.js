import React from 'react';
import LogoImage from './../../images/soccer-guess-logo.png';

/*
  LOGO COMPONENT
  A basic wrapper contenting the logo of the app
*/
const LogoComponent = () => {

  return (
    <div className="sg-component__logo">
      <img src={LogoImage} />
    </div>
  );
}

export default LogoComponent;
