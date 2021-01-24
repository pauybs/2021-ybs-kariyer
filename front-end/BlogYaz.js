import React, { Component, Fragment } from 'react';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,Jumbotron,Button, FormGroup, Label ,CustomInput } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import { Formik, Form, Field } from "formik";
import { NotificationManager } from "../../components/common/react-notifications";
import "rc-switch/assets/index.css";
import { NavLink } from "react-router-dom";
import SEO from "./seo";
import ReactQuill, { Quill } from 'react-quill'
import "react-quill/dist/quill.snow.css";
import 'react-quill/dist/quill.bubble.css';
import ImageResize from 'quill-image-resize-module-react'
import ImageUploader from "quill-image-uploader";
Quill.register('modules/imageResize', ImageResize)
Quill.register("modules/imageUploader", ImageUploader)


const quillFormats = [
  "header",
  "bold",
  "italic",
  "underline",
  "strike",
  "blockquote",
  "list",
  "bullet",
  "indent",
  "link",
  "image",
  "imageBlot", // #5 Optinal if using custom formats
  'video',
  'code-block',
];
class IlanVer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: true,
      loading: "",
      blogTitle: "",
      blogContent: "",
      selectedFile: [],
      selectedFileHome: null


    };
  }

  quillModules = {
    imageResize: {
      parchment: Quill.import('parchment'),
      modules: ['Resize', 'DisplaySize', 'Toolbar']
    },
    imageUploader: {
      upload: file => {
        return new Promise((resolve, reject) => {
          const formData = new FormData();
          formData.append("file", file);
          
          var client = require('../../client');
          client.post("blog-content-image", formData)
        .then(
            res => {
              resolve(res.data.data.url);
          
            },
            err => {
              reject("Upload failed");
            }
        )
        
        });
      }
    },
    toolbar: [
      [{ 'align': [] }],
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      ["bold", "italic", "underline", "strike", "blockquote"],
      [
        { list: "ordered" },
        { list: "bullet" },
        { indent: "-1" },
        { indent: "+1" }
      ],
      ["link","image","video"],
      ["clean"],
      ['code-block']
    ],
  
  };

  onFileChange = event => { 
    for (let i = 0; i < event.target.files.length; i++) {
          // Update the state 
   // Create a new array based on current state:
let selectedFile = this.state.selectedFile;

// Add item to it
const fileItem = event.target.files[i];
selectedFile.push({ fileItem });

// Set state
this.setState({ selectedFile });   
console.log('i= ',event.target.files[i]);
  }
   
  }; 

  onFileChangeHome = event => { 
    this.setState({selectedFileHome: event.target.files[0]});
  }; 

  handleChangeQuillStandart = (blogContent) => {
    this.setState({ blogContent });
  }
  componentDidMount()
  {
  
  }

  onBlogCreate = (values) => {

    if(!this.state.blogContent)
    {
      NotificationManager.warning(
        "Lütfen blog içeriği giriniz !",
        "Uyarı",
        3000,
        null,
        null,
        ''
      );
    }
    if(!this.state.selectedFileHome)
    {
      NotificationManager.warning(
        "Lütfen blog kapak resmi yükleyiniz !",
        "Uyarı",
        3000,
        null,
        null,
        ''
      );
    }

    if(this.state.selectedFile && this.state.selectedFile.length > 2)
    {
      NotificationManager.warning(
        "Lütfen blog ekstra resimi 2 den fazla olamaz!",
        "Uyarı",
        3000,
        null,
        null,
        ''
      );
    }
    

     
    if (
      values.blogTitle !== "" && this.state.blogContent !== "" && this.state.selectedFileHome !== "" 
      ) {
      this.setState({loading : 1}); 
   
     var bodyFormData = new FormData();
     bodyFormData.append('blogTitle', values.blogTitle);
     bodyFormData.append('blogContent', this.state.blogContent);
     if(this.state.selectedFile.length > 0)
     {
   for (let i = 0; i < this.state.selectedFile.length; i++) {
     console.log('test = ',this.state.selectedFile[i].fileItem)
     bodyFormData.append(`file[${i}]`, this.state.selectedFile[i].fileItem)
  }
     } else {
       bodyFormData.append(`file[0]`, null);
     }
     bodyFormData.append('fileHome', this.state.selectedFileHome)
    
      var client = require('../../client');

      client.post("add-blog", bodyFormData)
        .then(
            res => {
              
              this.setState({loading : null});
              console.log(res.data); 
              NotificationManager.success(
                'Blog başarılı şekilde eklendi!',
                "Başarılı",
                3000,
                null,
                null,
                ''
              );
              // this.props.history.push('/admin/universite');
            },
            err => {
              this.setState({loading : null}); 
              if(err.response.data.message)
              {
                err.response.data.message.map((message, i) => {     
                  NotificationManager.error(
                    message,
                    "Hata",
                    3000,
                    null,
                    null,
                    ''
                  );
               })
              }
            
                if(err.response === undefined){
                  NotificationManager.error(
                    'Sunucuya bağlanılırken hata oluştu !',
                    "Sunucu Hatası",
                    3000,
                    null,
                    null,
                    ''
                  );
                }
            }
        )
        values.blogTitle = "";
     
        
    this.setState({blogContent: ""});
    this.setState({selectedFile: []});
    this.setState({selectedFileHome: null});
    }
    
}

  validateBlogTitle = (value) => {
    let error;
    if (!value) {
      error = "Lütfen blog başlığı giriniz !";
    } else if (value.length < 2) {
      error = "Blog başlığı 2 karakterden az olamaz !";
    }
    return error;
  }


  render() {
    const { blogTitle,blogContent,loading } = this.state;
    const initialValues = {blogTitle,blogContent};
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
        <SEO 
 title="Blog Yaz" 
 description="Yönetim Bilişim Sistemleri blog yazıları"
 />
        <Row>
         
          <Colxx xxs="12">
          
            <Breadcrumb heading="İş İlanı Ekle" match={this.props.match} />
            
            <br></br>
            <NavLink to="/blog">
            <Button  outline color="secondary" className="mb-2 m-1">
                 Blog Yazıları
                </Button>
              </NavLink>
            
            <br></br>
            <Separator className="mb-1" />
          </Colxx>
        </Row>
        <Row>

        <Colxx xxs="12 p-2" className="mb-4">
            <Card>
              <CardBody>
           
                <Jumbotron>
                
                <Formik
                initialValues={initialValues}
                onSubmit={this.onBlogCreate}>
                {({ errors, touched }) => (
                  <Form className="av-tooltip tooltip-label-bottom">
                      
                     
         
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Blog Başlığı
                      </Label>
                      <Field
                        className="form-control"
                        name="blogTitle"
                        validate={this.validateBlogTitle}
                      />
                      {errors.blogTitle && touched.blogTitle && (
                        <div className="invalid-feedback d-block">
                          {errors.blogTitle}
                        </div>
                      )}
                    </FormGroup>
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Blog Yazınız
                      </Label>
                    <ReactQuill
                  theme="snow"
                  value={this.state.blogContent}
                  onChange={this.handleChangeQuillStandart}
                  modules={this.quillModules}
                  formats={quillFormats}
                  />
                    </FormGroup>
                    Kapak Resmini yükleyiniz
                    <input type="file" onChange={this.onFileChangeHome} />
                    <br></br>
                    Ekstra Resim Yüklemek İçin
                    <input type="file" multiple onChange={this.onFileChange} /> 
                    <div className="d-flex justify-content-between align-items-center">
                     
                      <Button
                        color="primary"
                        className={`btn-shadow btn-multiple-state ${this.state.loading ? "show-spinner" : ""}`}
                        size="lg"
                      >
                        <span className="spinner d-inline-block">
                          <span className="bounce1" />
                          <span className="bounce2" />
                          <span className="bounce3" />
                        </span>
                        <span className="label">Blog Ekle</span>
                      </Button>
                    </div>


                  </Form>
                )}
              </Formik>
                  </Jumbotron>
                  </CardBody>
                  </Card>
                  </Colxx>
        </Row>
      </Fragment>
    );
  }
}
export default injectIntl(IlanVer);
