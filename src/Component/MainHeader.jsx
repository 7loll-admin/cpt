import React from "react";

import {Menu, Layout, Button} from 'antd';

import { SettingOutlined, ContactsOutlined, CodeOutlined } from '@ant-design/icons';

import {useStateValue} from "../Utils/StateProvider";

import * as Types from "../Utils/actionType";
import {clearCache} from "../Utils/Data";

const { Header } = Layout;

function MainHeader() {

    const [stateValue, dispatch] = useStateValue();

    const menuItemStyle = {
        borderRadius: 0,
        paddingInline: '10px',
    }

    return (

        <Header className="header" style={{
            paddingInline: 0,
        }}>
            <div className="logo" style={{
                height: '40px',
                margin: '10px',
                background: 'rgba(255, 255, 255, 0.2)'
            }}>
            </div>
            <Menu
                style={{
                    borderRadius: '0px',
                }}
                theme="dark"
                mode="inline"
                defaultSelectedKeys={[stateValue.generalData.selectedMenu]}
                items={[
                    {
                        key: 'settings',
                        label: 'Settings',
                        icon: <SettingOutlined />,
                        style: menuItemStyle
                    },
                    {
                        key: 'shortcode',
                        label: 'Shortcode',
                        icon: <CodeOutlined />,
                        style: menuItemStyle
                    },

                    {
                        key: 'needsupport',
                        label: 'Need Support',
                        icon: <ContactsOutlined />,
                        style: menuItemStyle,
                    }

                ]}
                onSelect={ ({ item, key, keyPath, selectedKeys, domEvent }) => {
                    dispatch({
                        type: Types.GENERAL_DATA,
                        generalData:{
                            ...stateValue.generalData,
                            selectedMenu : key
                        }
                    });
                    localStorage.setItem( "cptwi_current_menu", key );
                } }
            />
            <Button
                type="primary"
                size="large"
                onClick={ () => clearCache() }
                style={{
                    color: '#fff',
                    position: 'absolute',
                    bottom: '5px',
                    left: '5px',
                    right: '5px',
                    borderRadius: 0,
                }}
            >
                Clear Cache
            </Button>
        </Header>
    );
}

export default MainHeader;