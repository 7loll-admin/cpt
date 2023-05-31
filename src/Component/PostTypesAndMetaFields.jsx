import React from 'react';
import { Button, Col, Select, Row, Typography, Spin } from 'antd';
import {useStateValue} from "../Utils/StateProvider";
import * as Types from "../Utils/actionType";
import {getPostMetas, notifications} from "../Utils/Data";

const {
    Paragraph
} = Typography;

const PostTypesAndMetaFields = () => {

    const [ stateValue, dispatch ] = useStateValue();

    let selectedType = Object.entries( stateValue.options.selected_post_types );

    const defauylFields  = [
        [
            {
                value: '',
                label: '',
            },
            {
                value: '',
                label: '',
            }
        ]
    ];

    const getFields = selectedType.map(([key, value]) =>{
        return [
            {
                value: key,
                label: key,
            },
            {
                value: value,
                label: value,
            }
        ];
    });

    const fields = selectedType.length ? getFields : defauylFields;

    const handleChangePostType = ( value, index, field ) => {
        let selectedPostTypes = stateValue.options.selected_post_types;
        const keys = Object.keys( selectedPostTypes );
        const oldKey = keys[index];

        const isBookIncluded = keys.includes( value );

        if( isBookIncluded ){
            notifications( false, 'Post type already selected');
            return;
        }

        selectedPostTypes = Object.fromEntries(
            Object.entries( selectedPostTypes ).map(([key, val]) => {
                if (key === oldKey) {
                    return [value, ''];
                }
                return [key, val];
            })
        );
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: selectedPostTypes
            }
        });

    };

    // Change Meta Key for price.
    const handleChangePostMeta = ( value, index, field ) => {
        let selectedPostTypes = stateValue.options.selected_post_types;
        const keys = Object.keys( selectedPostTypes );
        const currentKey = keys[index];
        selectedPostTypes[currentKey] = value
        // selectedPostTypes
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: selectedPostTypes
            }
        });

    };

    const handleAddField = () => {
        const isIncluded = Object.keys( stateValue.options.selected_post_types ).includes( '' );
        if( isIncluded ){
            notifications( false, 'Already Added. Please fill-up then add new one');
            return;
        }
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: {
                    ...stateValue.options.selected_post_types,
                    '': '',
                }
            }
        });
    };

    const handleRemoveField = postype => {
        const selected_post_types = stateValue.options.selected_post_types;
        delete selected_post_types[postype];
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: selected_post_types
            }
        });
    };

    const getTheMeta = async ( postType, index, field ) => {
        await dispatch({
            type: Types.GENERAL_DATA,
            //stateValue.generalData.postTypesMeta
            generalData: {
                ...stateValue.generalData,
                postTypesMeta: [],
                isLoading: true
            }
        });
        const response = await getPostMetas( {
            post_type : postType
        });
        await dispatch({
            type: Types.GENERAL_DATA,
            //stateValue.generalData.postTypesMeta
            generalData: {
                ...stateValue.generalData,
                postTypesMeta: response,
                isLoading: false
            }
        });
    };

    return (
        <>
            { fields.map( ( field, index) => (
                <Row key={index} gutter={16} style={{ marginBottom: '16px'}}>
                    <Col className="gutter-row" span={10}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px'}}>
                            Post Type
                        </Paragraph>
                        <Select
                            size="large"
                            placeholder='Post Type'
                            value={field[0].value}
                            style={{ width: '100%', height: '40px' }}
                            options={stateValue.generalData.postTypes}
                            onChange={ ( value ) => handleChangePostType( value, index, field ) }
                        />
                    </Col>
                    <Col className="gutter-row" span={10}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px'}}>
                            Meta key for Price
                        </Paragraph>
                        <Select
                            showSearch
                            size="large"
                            allowClear = {true}
                            placeholder='Select Post Meta Key'
                            style={{ width: '100%', height: '40px', padding: 0 }}
                            value={field[1].value}
                            notFoundContent={ stateValue.generalData.isLoading ?
                                <Spin size="small"
                                      style={{
                                          position: 'relative',
                                          display: 'inline-block',
                                          opacity: 1,
                                          left: '50%',
                                          margin: '30px auto',
                                          width: '50px',
                                          transform: 'translateX( -50% )'
                                        }}
                                /> : null
                            }
                            onFocus={ () => getTheMeta( field[0].value, index, field ) }
                            onChange={ ( value ) => handleChangePostMeta( value, index, field ) }
                            options={stateValue.generalData.postTypesMeta}
                        />
                    </Col>
                    <Col className="gutter-row" span={4}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px', opacity: 0 }}>
                          Remove
                        </Paragraph>
                        <Button
                            style={{ width: '100%', height: '40px' }}
                            onClick={() => handleRemoveField( field[0].value )}
                        >Remove</Button>
                    </Col>
                </Row>
            )) }
            <Row gutter={16}>
                <Col className="gutter-row" span={12}>
                    <Button
                        style={{ height: '40px' }}
                        onClick={handleAddField}
                    >Add New</Button>
                </Col>
            </Row>
        </>
    );
};

export default PostTypesAndMetaFields;
