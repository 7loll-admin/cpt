import React from 'react';

import { useStateValue } from '../Utils/StateProvider';

import Loader from '../Utils/Loader';

import {
    Form,
    Layout,
    Button,
    Divider,
    Checkbox,
    Typography
} from 'antd';

const { Title, Text } = Typography;

const { Content } = Layout;

const CheckboxGroup = Checkbox.Group;

import * as Types from "../Utils/actionType";

import PostTypesAndMetaFields from "./PostTypesAndMetaFields";

function Settings() {

   const [stateValue, dispatch] = useStateValue();

    const priceCartPosition = (e) => {
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options : {
                ...stateValue.options,
                [e.target.name]: stateValue.options[e.target.name] !== e.target.value ? e.target.value : '',
            }
        });
        // dispatch({
        //     type: Types.UPDATE_OPTIONS,
        //     options : {
        //         ...stateValue.options,
        //         [e.target.name]: stateValue.options[e.target.name] !== e.target.value ? e.target.value : '',
        //     }
        // });
    };

    const onChangePostTypesList = (list, key) => {
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options : {
                ...stateValue.options,
                [key]: list,
            }
        });

    };

    return (
        <Layout style={{ position: 'relative' }}>
            <Form
                labelCol={{
                    span: 8,
                    offset: 0,
                    style:{
                        textAlign: 'left',
                        wordWrap: 'wrap',
                        fontSize:'16px'
                    }
                }}
                wrapperCol={{ span: 24 }}
                layout="horizontal"
                style={{
                    maxWidth: 950,
                    padding: '15px',
                    height: '100%'
                }}
            >
                { stateValue.options.isLoading ? <Loader/> :
                    <Content style={{
                        padding: '15px',
                        background: 'rgb(255 255 255 / 35%)',
                        borderRadius: '5px',
                        boxShadow: 'rgb(0 0 0 / 1%) 0px 0 20px',
                    }}>
                        <Form.Item label={<Title level={5} style={{ margin:0, fontSize:'16px' }}>  Select Post type & Price Meta key </Title>} >
                            <PostTypesAndMetaFields/>
                        </Form.Item>
                        <Divider orientation="left"></Divider>
                        <Form.Item label={<Title level={5} style={{ margin:0, fontSize:'16px' }}> Show Price </Title>} >
                            <Checkbox
                                onChange={priceCartPosition}
                                name={`price_position`}
                                value={`price_after_content`}
                                checked={ 'price_after_content' === stateValue.options.price_position }>
                                Display Price After Content
                            </Checkbox>

                            { 'price_after_content' === stateValue.options.price_position &&
                                <>
                                    <Divider />
                                    <CheckboxGroup
                                        options={stateValue.generalData.postTypes}
                                        value={stateValue.options.price_after_content_post_types}
                                        onChange={ (list) => onChangePostTypesList(list, 'price_after_content_post_types' ) }
                                    />
                                </>
                            }
                            <Divider orientation="left"></Divider>
                            <Text type="secondary" >
                                ALso Can Use Shortcode
                            </Text>

                        </Form.Item>
                         <Divider orientation="left"></Divider>
                        <Form.Item label={<Title level={5} style={{ margin:0, fontSize:'16px' }}> Show Cart Button </Title>} >
                            <Checkbox
                                onChange={priceCartPosition}
                                name={`cart_button_position`}
                                value={`cart_button_after_content`}
                                checked={ 'cart_button_after_content' === stateValue.options.cart_button_position }>
                                Display Cart Button After Content
                            </Checkbox>

                            { 'cart_button_after_content' === stateValue.options.cart_button_position &&
                                <>
                                    <Divider />
                                    <CheckboxGroup
                                        options={stateValue.generalData.postTypes}
                                        value={stateValue.options.cart_button_after_content_post_types}
                                        onChange={ (list) => onChangePostTypesList( list, 'cart_button_after_content_post_types' ) }
                                    />
                                </>
                            }
                            <Divider orientation="left"></Divider>
                            <Text type="secondary" >
                                ALso Can Use Shortcode
                            </Text>

                        </Form.Item>
                    </Content>
                }

            </Form>

            <Button
                type="primary"
                size="large"
                style={{
                    position: 'absolute',
                    bottom: '15px',
                    right: '15px'
                }}
                onClick={ () => dispatch({
                    ...stateValue,
                    type: Types.UPDATE_OPTIONS,
                    saveType: Types.UPDATE_OPTIONS,
                }) } >
                Save Settings
            </Button>
        </Layout>

    );
};

export default Settings;