const { element } = wp;
import Boxes from './components/Boxes';
import './store';

const DynamicBlock = (props) => {
    return (
        <div>
            <Boxes
                alignment={props.alignment}
                displayNumber={props.displayNumber}
                backgroundColor={props.backgroundColor}
            ></Boxes>
        </div>
    );
}

window.__dynamicBlockReload = function () {
    let container = document.getElementById('dynamic-block');
    if (container) {
        let alignment = container.getAttribute('data-alignment');
        let displayNumber = container.getAttribute('data-display-number');
        let backgroundColor = container.getAttribute('data-background-color');
        element.render(
            <DynamicBlock alignment={alignment} displayNumber={displayNumber} backgroundColor={backgroundColor} />,
            container
        )
    }
};

window.onload = window.__dynamicBlockReload;
