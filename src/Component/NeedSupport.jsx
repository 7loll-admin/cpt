import React from 'react';

import { useStateValue } from '../Utils/StateProvider';

import Loader from '../Utils/Loader';

import {
    Form,
    Input,
    Layout,
    Button,
    Divider,
    Checkbox,
    Typography
} from 'antd';

function NeedSupport() {

    const [stateValue, dispatch] = useStateValue();

    return (
        <Layout style={{ position: 'relative' }}>

            For faster support please send detail of your issue.

            Email: <a href={`mailto:support@tinysolutions.freshdesk.com`}> support@tinysolutions.freshdesk.com </a>

            This will create a ticket. We will response form there.

            Check our  <a href={`mailto:support@tinysolutions.freshdesk.com`}> Plugins List </a>

        </Layout>

    );
};

export default NeedSupport;