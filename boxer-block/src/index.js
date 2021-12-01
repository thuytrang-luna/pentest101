const { __ } = wp.i18n;
const {
    registerBlockType,
} = wp.blocks;


import edit from './edit';
import save from './save';
import './store';

registerBlockType('boxer/boxer', {
    title: __('Boxer', 'boxer'),
    icon: 'archive',
    category: 'layout',
    attributes: {
        alignment: {
            type: 'string',
            default: 'none'
        },
        displayNumber: {
            type: 'integer',
            default: 3
        },
        backgroundColor: {
            type: 'string',
            default: 'white', // Default value for newly added block
        }
    },
    keywords: [],
    supports: {},
    edit: edit,
    save: save
});