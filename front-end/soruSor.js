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
Quill.register('modules/imageResize', ImageResize)
const quillModules = {
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
    
    ["clean"]
  ],
  imageResize: {
    // parchment: Quill.import('parchment'),
    modules: [ 'Resize', 'DisplaySize' ]
}
};

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
  "image"
];
class SoruSor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: true,
      loading: "",
      questionTitle: "",
      questionContent: "",
    };
  }

  handleChangeQuillStandart = (questionContent) => {
    this.setState({ questionContent });
  }
  componentDidMount()
  {

  }

  onQuestionCreate = (values) => {
    if (values.questionTitle !== "" && this.state.questionContent !== "") {
      this.setState({loading : 1}); 
   
     var bodyFormData = new FormData();
     bodyFormData.append('questionTitle', values.questionTitle);
     bodyFormData.append('questionContent', this.state.questionContent);
    const options = {
       
      headers: {
      'Content-Type': 'application/json',
      }
      }
      var client = require('../../client');

      client.post("add-question", bodyFormData, options)
        .then(
            res => {
              
              this.setState({loading : null});
              console.log(res.data); 
              NotificationManager.success(
                'Soru başarılı şekilde soruldu!',
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
        values.questionTitle = "";
    this.setState({questionContent: ""});
    }
    
}

  validateQuestionTitle = (value) => {
    let error;
    if (!value) {
      error = "Lütfen soru başlığını yazınız!";
    } else if (value.length < 2) {
      error = "Soru başlığı 2 karakterden az olamaz !";
    }
    return error;
  }




  render() {
    const { questionTitle,questionContent,loading } = this.state;
    const initialValues = {questionTitle,questionContent};
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
        <SEO 
 title="Soru Sor" 
 description="Yönetim Bilişim Sistemleri Soru Cevap YbsKariyer.com"
 />
        <Row>
         
          <Colxx xxs="12">
          
            <Breadcrumb heading="Soru Sor" match={this.props.match} />
            
            <br></br>
            <NavLink to="/sorular">
            <Button  outline color="secondary" className="mb-2 m-1">
                 Sorulan Sorular
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
                onSubmit={this.onQuestionCreate}>
                {({ errors, touched }) => (
                  <Form className="av-tooltip tooltip-label-bottom">
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Soru Başlığı
                      </Label>
                      <Field
                        className="form-control"
                        name="questionTitle"
                        validate={this.validateQuestionTitle}
                      />
                      {errors.questionTitle && touched.questionTitle && (
                        <div className="invalid-feedback d-block">
                          {errors.questionTitle}
                        </div>
                      )}
                    </FormGroup>
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Sorunuz
                      </Label>
                    <ReactQuill
                  theme="snow"
                  value={this.state.questionContent}
                  onChange={this.handleChangeQuillStandart}
                  modules={quillModules}
                  formats={quillFormats}
                  />
                    </FormGroup>
                    
                    
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
                        <span className="label">Soru Sor</span>
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
export default injectIntl(SoruSor);
