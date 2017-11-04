import './scss/_export.scss';
import React from 'react';
import ReactDOM from 'react-dom';
import Logo from './js/components/logo.component';

const SoccerGuess = () => {

  return (
      <div>
        <Logo/>
      </div>
    );
};

ReactDOM.render(
    <SoccerGuess/>,
    document.querySelector('#app')
);
