import React, { useEffect } from "react";

import {Button, Checkbox, Space, Input, Layout } from "antd";

import {useStateValue} from "./StateProvider";

import * as Types from "./actionType";

const { TextArea } = Input;

export const headerStyle = {
    height: 64,
    paddingInline: 10,
    lineHeight: '64px',
    backgroundColor: '#fff',
};

export const selectStyle = {
    width: 160,
    paddingInline: 0,
}

export const defaultBulkSubmitData = {
    bulkChecked : false,
    isModalOpen : false,
    ids: [],
    type: '',
    data : {
        post_title : '',
        alt_text : '',
        caption : '',
        post_description : '',
    },
    post_categories : [],
}

export const bulkOprions = [
    {
        value: '',
        label: 'Bulk actions',
    },
    {
        value: 'bulkedit',
        label: 'Bulk Edit',
    },
    {
        value: 'trash',
        label: 'Move to Trash',
    },
    {
        value: 'inherit',
        label: 'Restore',
    },
    {
        value: 'delete',
        label: 'Delete Permanently ',
    },
];

export const columnList = [
    {
        title: 'ID',
        key: 'ID',
    },
    {
        title: 'File',
        key: 'Image',
    },
    {
        title: `Title`,
        key: 'Title',
    },
    {
        title: `Alt`,
        key: 'Alt',
    },
    {
        title: `Caption`,
        key: 'Caption',
    },
    {
        title: `Description`,
        key: 'Description',
    },
    {
        title: `Category`,
        key: 'Category',
    },
];

const theImage = ( record ) => {
    let type = record.post_mime_type.split("/"),
        width = 40,
        url;
    type = Array.isArray( type ) ? type[0] : '';
    switch ( type ) {
        case 'image':
            url = record.guid;
            width = 60;
            break;
        case 'audio':
            url = `${cptwoointParams.includesUrl}/images/media/audio.png`
            break;
        case 'video':
            url = `${cptwoointParams.includesUrl}/images/media/video.png`
            break;
        case 'application':
            if( 'application/zip' === record.post_mime_type ){
                url = `${cptwoointParams.includesUrl}/images/media/archive.png`
            } else if ( 'application/pdf' === record.post_mime_type  ){
                url = `${cptwoointParams.includesUrl}/images/media/document.png`
            }
            break;
        case 'text':
            url = `${cptwoointParams.includesUrl}/images/media/text.png`
            break;
        default:
            url = `${cptwoointParams.includesUrl}/images/media/text.png`
    }

    return <img width={ width } src={url} /> ;

};

export function columns(){

    const [stateValue, dispatch] = useStateValue();

    const onCheckboxChange = (event) => {
        const value = event.target.value ;
        const changeData = event.target.checked ? [
            ...stateValue.bulkSubmitData.ids,
            value
        ] : stateValue.bulkSubmitData.ids.filter( item => item !== value );

        const checkedCount = Object.keys( changeData ).length;
        const postCount = Object.keys( stateValue.mediaData.posts ).length;

        dispatch({
            type: Types.BULK_SUBMIT,
            bulkSubmitData: {
                ...stateValue.bulkSubmitData,
                bulkChecked : checkedCount && checkedCount === postCount,
                ids: changeData
            },
        });

    };

    const onBulkCheck = (event) => {
        const postsId = event.target.checked ? stateValue.mediaData.posts.map( item => item.ID ) : [];
        dispatch({
            type: Types.BULK_SUBMIT,
            bulkSubmitData: {
                ...stateValue.bulkSubmitData,
                bulkChecked : ! ! postsId.length,
                ids: postsId
            },
        });
    };
    const handleSortClick = ( odrby ) => {
        const { orderby, order } = stateValue.mediaData.postQuery;
        dispatch({
            type: Types.GET_MEDIA_LIST,
            mediaData: {
                ...stateValue.mediaData,
                postQuery : {
                    ...stateValue.mediaData.postQuery,
                    orderby: odrby,
                    paged: 1,
                    order: odrby === orderby && 'DESC' === order ? 'ASC' : 'DESC',
                }
            },
        });
    };

    const handleChange = ( event ) => {
        const currentItem = parseInt( event.target.getAttribute('current') );
        let posts = stateValue.mediaData.posts;
        let currentData = {
            ID: posts[currentItem].ID,
            [event.target.name] : event.target.value.trim()
        }

        posts[currentItem][event.target.name] = event.target.value;

        dispatch({
            type: Types.GET_MEDIA_LIST,
            mediaData: {
                ...stateValue.mediaData,
                posts: posts,
                isLoading: false
            },
        });

        dispatch({
            type: Types.UPDATE_SINGLE_MEDIA,
            singleMedia: {
                ...stateValue.singleMedia,
                ...currentData,
            }
        });

    }
    const handleFocusout = ( event ) => {
        dispatch({
            ...stateValue,
            type: Types.UPDATE_SINGLE_MEDIA,
            saveType: Types.UPDATE_SINGLE_MEDIA,
        });
    }

   const formEdited = stateValue.singleMedia.formEdited;

    return [

        {
            title: <Checkbox checked={ stateValue.bulkSubmitData.bulkChecked } onChange={onBulkCheck}/>,
            key: 'CheckboxID',
            dataIndex: 'ID',
            width: '50px',
            align: 'center',
            render:  ( id, record ) => <Checkbox checked={ -1 !== stateValue.bulkSubmitData.ids.indexOf( id ) } name="item_id" value={id} onChange={onCheckboxChange} />
        },
        {
            title: <Space wrap> { `ID` } <Button size={`small`} onClick={ ( event ) => handleSortClick('id') }> {`Sort`} </Button> </Space>,
            key: 'ID',
            dataIndex: 'ID',
            width: '150px',
            align: 'top'
        },
        {
            title: 'File',
            key: 'Image',
            dataIndex: 'guid',
            width: '150px',
            align: 'top',
            render: ( text, record, i ) => <Space> { theImage( record ) }</Space>,
        },
        {
            title: <Space wrap> { `Title` } <Button size={`small`} onClick={ ( event ) => handleSortClick('title') }> Sort </Button> </Space>,
            key: 'Title',
            dataIndex: 'post_title',
            align: 'top',
            width: '300px',
            render: ( text, record, i ) => <> { formEdited ? <TextArea name={`post_title`} placeholder={`Title Shouldn't leave empty`} current={i} onBlur={handleFocusout} onChange={handleChange} value={ text } /> : text }   </>
        },
        {
            title: <Space wrap> { `Alt` } <Button size={`small`} onClick={ ( event ) => handleSortClick('title') }> Sort </Button> </Space>,
            key: 'Alt',
            dataIndex: 'alt_text',
            align: 'top',
            width: '300px',
            render: ( text, record, i ) => <> { formEdited ? <TextArea name={`alt_text`} placeholder={`Alt Text Shouldn't leave empty`} current={i} onBlur={handleFocusout}  onChange={handleChange} value={ text } /> : text } </>
        },
        {
            title: <Space wrap> { `Caption` } <Button size={`small`} onClick={ ( event ) => handleSortClick('caption') }> Sort </Button> </Space>,
            key: 'Caption',
            dataIndex: 'post_excerpt',
            width: '300px',
            render: ( text, record, i ) => <> { formEdited ? <TextArea name={`post_excerpt`} placeholder={`Caption Text`} current={i} onBlur={handleFocusout}  onChange={handleChange} value={ text } /> : text }   </>
        },
        {
            title: <Space wrap> { `Description` } <Button size={`small`} onClick={  ( event ) => handleSortClick('description') }> Sort </Button> </Space>,
            key: 'Description',
            dataIndex: 'post_content',
            width: '350px',
            render: ( text, record, i ) => <> { formEdited ? <TextArea name={`post_content`} placeholder={`Description Text`} current={i} onBlur={handleFocusout}  onChange={handleChange} value={ text } /> : text }   </>
        },
        {
            title: <Space wrap> { `Category` } </Space>,
            key: 'Category',
            dataIndex: 'categories',
            width: '250px',
            render: ( text, record, i ) => {
                const items = JSON.parse(record.categories)
                return  items.map( item => item.id && <Button key={Math.random().toString(36).substr(2, 9)} size={`small`} > {  item.name } </Button>  )
            }
        },
    ];
}


export function renamerColumns(){

    const [stateValue, dispatch] = useStateValue();

    return [
        {
            title: <Space wrap> { `ID` } </Space>,
            key: 'ID',
            dataIndex: 'ID',
            width: '100px',
            align: 'top'
        },
        {
            title: 'File',
            key: 'Image',
            dataIndex: 'guid',
            width: '130px',
            align: 'top',
            render:  ( text, record, i ) => <Space> { theImage( record ) }</Space>,
        },
        {
            title: `File Name`,
            key: 'Image',
            dataIndex: 'guid',
            width: '400px',
            align: 'top',
            render:  ( text, record, i ) =>  <>
                { stateValue.rename.formEdited ?  <Layout style={{
                    display: 'flex',
                    flexDirection: 'row',
                    alignItems: 'center',
                    background: 'transparent'
                }}>
                    <Input
                        size="large"
                        name={`filebasename`}
                        placeholder={`The name Shouldn't leave empty`}
                        current={i}
                        style={{ maxWidth: 'calc( 100% - 50px)' }}
                        onBlur={
                            () => dispatch({
                                ...stateValue,
                                type: Types.UPDATE_RENAMER_MEDIA,
                                saveType: Types.UPDATE_RENAMER_MEDIA
                            })
                        }
                        onChange={
                            ( event ) => {
                                const currentItem = parseInt( event.target.getAttribute('current') );
                                if( 'filebasename' ===  event.target.name ){
                                    const pnlname = stateValue.mediaData.posts[currentItem].thefile;
                                    stateValue.mediaData.posts[currentItem].thefile.filebasename = event.target.value;
                                    dispatch({
                                        type: Types.UPDATE_RENAMER_MEDIA,
                                        rename : {
                                            ...stateValue.rename,
                                            postsdata: pnlname,
                                            ID: stateValue.mediaData.posts[currentItem].ID,
                                            newname: event.target.value
                                        }
                                    });
                                }
                            }
                        }
                        value={ record.thefile.filebasename }
                    />
                    {`.${record.thefile.fileextension}`}
                </Layout> : record.thefile.mainfilename }
            </>,
        },
        {
            title: <Space wrap> { `Title` } </Space>,
            key: 'Title',
            dataIndex: 'post_title',
            align: 'top',
        }
    ];
}
