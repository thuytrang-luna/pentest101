const save = props => {
    const {
        className,
        attributes: { alignment, displayNumber, backgroundColor }
    } = props;

    setTimeout(function () {

        if (window.__dynamicBlockReload) {
            window.__dynamicBlockReload();
        }
    });
    return (
        <div>
            <div
                className={className}
                data-alignment={alignment}
                data-display-number={displayNumber}
                data-background-color={backgroundColor}
                id={'dynamic-block'}
            >
            </div>
        </div>
    );
}

export default save
