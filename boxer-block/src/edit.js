import { Component, Fragment } from '@wordpress/element';
const { withSelect } = wp.data;
import { isUndefined, pickBy } from 'lodash'
import Boxes from './components/Boxes';

const { __ } = wp.i18n;

const {
    AlignmentToolbar,
    BlockControls,
    InspectorControls,
    ColorPalette
} = wp.editor;

const {
    RangeControl
} = wp.components;

class Edit extends Component {

    constructor() {
        super(...arguments);
        this.state = {
        };
    }

    render() {
        const {
            attributes: {
                alignment,
                displayNumber,
                backgroundColor
            },
            className,
            latestPosts,
            setAttributes
        } = this.props;

        return (
            <Fragment>
                {
                    <InspectorControls>
                        <RangeControl
                            label={__('Number of posts displayed')}
                            value={displayNumber}
                            onChange={displayNumber => setAttributes({ displayNumber })}
                            min={1}
                            max={12}
                        />
                        <label className="blocks-base-control__label">background color</label>
                        <ColorPalette // Element Tag for Gutenberg standard colour selector
                            value={backgroundColor}
                            onChange={backgroundColor => setAttributes({ backgroundColor })} // onChange event callback
                        />
                    </InspectorControls>
                }
                {
                    <BlockControls>
                        <AlignmentToolbar
                            value={alignment}
                            onChange={alignment => setAttributes({ alignment })}
                        />
                    </BlockControls>
                }
                {
                    <Boxes
                        alignment={alignment}
                        displayNumber={displayNumber}
                        backgroundColor={backgroundColor}
                    ></Boxes>
                }
            </Fragment>
        );
    }
}

export default Edit;
