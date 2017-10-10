import './scss/_export.scss';
import React from 'react';
import ReactDOM from 'react-dom';
import Photo from './images/img_to_import.jpg';

const Init = () => {
    return (
        <div>
            <p className="init">Hello, World!</p>
            <img src={Photo} />
        </div>
    );
};

ReactDOM.render(
    <Init/>,
    document.querySelector('#app')
);