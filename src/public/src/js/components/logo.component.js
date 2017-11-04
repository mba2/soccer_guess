import React from 'react';
import ImageLogo from './../../images/soccer-guess-logo.png';

/*
  LOGO COMPONENT
  A basic wrapper contenting the logo of the app
*/
const Logo = () => {

  return (
    <div>
      <img src={ImageLogo} />
    </div>
  );
}

export default Logo;
