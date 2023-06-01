import React from 'react';

import { useStateValue } from '../Utils/StateProvider';

import {
    Layout,
    Divider,
    Typography,
    InputNumber,
    ColorPicker,
    Button,
    Col,
    Row
} from 'antd';
import * as Types from "../Utils/actionType";

import Loader from "../Utils/Loader";

const { Title, Text, Paragraph } = Typography;

function StyleSection() {

   const [stateValue, dispatch] = useStateValue();

    const addStyle = ( value, selector ) => {
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options : {
                ...stateValue.options,
                style:{
                    ...stateValue.options.style,
                   [selector]: value,
                }
            }
        });
    };

    return (

        <Layout style={{
            padding: '80px',
            maxWidth: '600px',
            background: 'rgb(255 255 255 / 35%)',
            borderRadius: '5px',
            boxShadow: 'rgb(0 0 0 / 1%) 0px 0 20px'
        }}>
            { stateValue.options.isLoading ? <Loader/> :
                <>
                    <Title  level={4} style={{ margin:0, fontSize:'16px' }} > Cart Button & Quantity Field Style </Title>

                    <Divider style={{ marginBottom: '10px' }} orientation="left"></Divider>
                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Quantity Width: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} ><InputNumber min={15} max={150} value={ stateValue.options.style?.fieldWidth } onChange={ ( value ) => addStyle( value, 'fieldWidth' ) } /> px</Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button Width: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} ><InputNumber min={15} max={150} value={ stateValue.options.style?.buttonWidth } onChange={ ( value ) => addStyle( value, 'buttonWidth' ) } /> px</Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button And Quantity Height: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} ><InputNumber min={10} max={100} value={ stateValue.options.style?.fieldHeight } onChange={ ( value ) => addStyle( value, 'fieldHeight' ) } /> px</Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button And Quantity Gap: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} ><InputNumber min={0} max={100} value={ stateValue.options.style?.fieldGap } onChange={ ( value ) => addStyle( value, 'fieldGap' ) } /> px</Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button Text Color: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >
                                <ColorPicker format='hex' value={stateValue.options.style?.buttonColor} onChange={ ( colorHex ) => addStyle( colorHex.toHexString(), 'buttonColor' ) } /> Selected Color: {stateValue.options.style?.buttonColor}
                            </Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button Background Color: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >
                                <ColorPicker format='hex' value={stateValue.options.style?.buttonBgColor} onChange={ ( colorHex ) => addStyle( colorHex.toHexString(), 'buttonBgColor' ) } /> Selected Color: { stateValue.options.style?.buttonBgColor }
                            </Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button Text Hover Color: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >
                                <ColorPicker format='hex' value={stateValue.options.style?.buttonHoverColor} onChange={ ( colorHex ) => addStyle( colorHex.toHexString(), 'buttonHoverColor' ) } /> Selected Color: {stateValue.options.style?.buttonHoverColor}
                            </Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                    <Row gutter={16}>
                        <Col className="gutter-row" span={10}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >Button Background Hover Color: </Paragraph>
                        </Col>
                        <Col className="gutter-row" span={14}>
                            <Paragraph style={{ margin:0, fontSize:'16px' }} >
                                <ColorPicker format='hex' value={stateValue.options.style?.buttonHoverBgColor} onChange={ ( colorHex ) => addStyle( colorHex.toHexString(), 'buttonHoverBgColor' ) } /> Selected Color: { stateValue.options.style?.buttonHoverBgColor }
                            </Paragraph>
                        </Col>
                    </Row>
                    <Divider style={{ marginBottom: '10px',marginTop: '10px', }} orientation="left"></Divider>

                </>
            }
            <Button
                type="primary"
                size="large"
                style={{
                    position: 'fixed',
                    bottom: '100px',
                    right: '100px'
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

export default StyleSection;