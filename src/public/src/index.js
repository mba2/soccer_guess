import './scss/_export.scss';
import React from 'react';
import ReactDOM from 'react-dom';
import LoginPage from './js/pages/login.page';

const SoccerGuess = () => {

  return (
    <div>
      <LoginPage/>
    </div>
  );
};

ReactDOM.render(
  <SoccerGuess/>,
  document.querySelector('#app')
);
