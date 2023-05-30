import React from 'react';

import { useStateValue } from '../Utils/StateProvider';

import {
    Layout,
    Divider,
    Typography
} from 'antd';

const { Text, Paragraph } = Typography;

function Shortcode() {

   const [stateValue, dispatch] = useStateValue();

    return (
        <Layout
                style={{
                    padding: '80px',
                    background: 'rgb(255 255 255 / 35%)',
                    borderRadius: '5px',
                    boxShadow: 'rgb(0 0 0 / 1%) 0px 0 20px',
                }}
            >
            <Divider orientation="left"></Divider>
            <Paragraph  copyable={{ text: '[cptwooint_price/]' }} > Shortcode for display price <Text type="secondary" code style={{ fontSize: '20px' }} > [cptwooint_price/] </Text> </Paragraph>

            <Divider orientation="left"></Divider>
            <Paragraph  copyable={{ text: '[cptwooint_cart_button/]' }} > Shortcode for display cart button <Text type="secondary" code style={{ fontSize: '20px' }}> [cptwooint_cart_button/] </Text> </Paragraph>

        </Layout>

    );
};

export default Shortcode;