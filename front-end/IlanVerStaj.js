import React, { Component, Fragment } from 'react';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,Jumbotron,Button, FormGroup, Label ,CustomInput } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import { Formik, Form, Field } from "formik";
import { NotificationManager } from "../../components/common/react-notifications";
import Select from "react-select";
import CustomSelectInput from "../../components/common/CustomSelectInput";
import Switch from "rc-switch";
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
class IlanVer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: true,
      loading: "",
      internTitle: "",
      internContent: "",
      internCompany: "",
      internCity: "",
      internPosition: "",
      workplaceSector: "",
      internType: null,
      selectDataCity: [],
      selectDataPosition: [],
      selectDataworkplaceSector: []

    };
  }

  handleChangeQuillStandart = (internContent) => {
    this.setState({ internContent });
  }
  componentDidMount()
  {
    var client = require('../../client');
    client.get("list-city")
    .then(
      res =>{
        res.data.data.map((data, i) => {     
          var joined = this.state.selectDataCity.concat({ label: data.cityName, value: data.id });
          this.setState({ selectDataCity: joined })       
           
       });
      },
      err =>{
  
      }
    )
  
    client.get("list-position")
    .then(
      res =>{
        res.data.data.map((data, i) => {     
          var joined = this.state.selectDataPosition.concat({ label: data.positionName, value: data.id });
          this.setState({ selectDataPosition: joined })       
           
       });
      },
      err =>{
  
      }
    )

    client.get("list-sector")
  .then(
    res =>{
      res.data.data.map((data, i) => {     
        var joined = this.state.selectDataworkplaceSector.concat({ label: data.sectorName, value: data.id });
        this.setState({ selectDataworkplaceSector: joined })       
         
     });
    },
    err =>{

    }
  )
  }

  onInternAdCreate = (values) => {
    if(!this.state.internType)
    {
      NotificationManager.warning(
        "Lütfen İlan Tipini Seçiniz !",
        "Uyarı",
        3000,
        null,
        null,
        ''
      );
    }
    if(!this.state.internPosition.value)
      {
        NotificationManager.warning(
          "Lütfen Pozisyon seçiniz !",
          "Uyarı",
          3000,
          null,
          null,
          ''
        );
        return null;
      }

      if(!this.state.internCity.value)
      {
        NotificationManager.warning(
          "Lütfen  Şehir seçiniz !",
          "Uyarı",
          3000,
          null,
          null,
          ''
        );
        return null;
      }
      if(this.state.internType == 0 && !this.state.workplaceSector.value)
      {
        NotificationManager.warning(
          "Lütfen  Sektör seçiniz !",
          "Uyarı",
          3000,
          null,
          null,
          ''
        );
        return null;
      }
    if (
      values.internTitle !== "" && this.state.internContent !== "" 
      ) {
      this.setState({loading : 1}); 
   
     var bodyFormData = new FormData();
     bodyFormData.append('internTitle', values.internTitle);
     bodyFormData.append('internContent', this.state.internContent);
     bodyFormData.append('internCompany', values.internCompany);
     bodyFormData.append('internCity', this.state.internCity.value);
     bodyFormData.append('internPosition',  this.state.internPosition.value);
     bodyFormData.append('internType', this.state.internType);
     if(!this.state.workplaceSector.value)
     {
       var workplaceSector = "";
     }else {
       var workplaceSector = this.state.workplaceSector.value;
     }
     bodyFormData.append('workplaceSector', workplaceSector);
    const options = {
       
      headers: {
      'Content-Type': 'application/json',
      }
      }
      var client = require('../../client');

      client.post("add-intern-ad", bodyFormData, options)
        .then(
            res => {
              
              this.setState({loading : null});
              console.log(res.data); 
              NotificationManager.success(
                'İlan başarılı şekilde eklendi!',
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
        values.internTitle = "";
        values.internCompany = "";
        values.internCity = "";
        values.internPosition = "";
        values.internType = "";
        
    this.setState({internContent: ""});
    this.setState({internCity: ""});
    this.setState({internPosition: ""});
    this.setState({workplaceSector: ""})
    }
    
}

  validateInternTitle = (value) => {
    let error;
    if (!value) {
      error = "Lütfen ilan başlığını yazınız!";
    } else if (value.length < 2) {
      error = "İlan başlığı 2 karakterden az olamaz !";
    }
    return error;
  }

  validateInternCompany = (value) => {
    let error;
    if (!value) {
      error = "Lütfen şirket ismi yazınız!";
    } else if (value.length < 2) {
      error = "Şirket ismi 2 karakterden az olamaz !";
    }
    return error;
  }

  onSiteChanged =  (e)  => {
    this.setState({
      internType: e.currentTarget.value
      });
  }


  handleChangeCity = internCity => {
    this.setState({ internCity });
  };

  handleChangePosition = internPosition => {
    this.setState({ internPosition });
  };

  handleChangeworkplaceSector = workplaceSector => {
    this.setState({ workplaceSector });
  };
  render() {
    const { internTitle,internContent,internCompany,internCity,internPosition,internType,loading,workplaceSector } = this.state;
    const initialValues = {internTitle,internContent,internCompany,internCity,internPosition,internType,workplaceSector};
    return !this.state.isLoading && !this.state.selectDataCity && !this.state.selectDataPosition ? (
      <div className="loading" />
    ) : (
      <Fragment>
               <SEO 
 title="İş İlanı Ver" 
 description="Yönetim Bilişim Sistemleri İş İlanları"
 />
        <Row>
         
          <Colxx xxs="12">
          
            <Breadcrumb heading="Staj İlanı Ekle" match={this.props.match} />
            
            <br></br>
            <NavLink to="/staj-ilanlari">
            <Button  outline color="secondary" className="mb-2 m-1">
                 Staj İlanları
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
                onSubmit={this.onInternAdCreate}>
                {({ errors, touched }) => (
                  <Form className="av-tooltip tooltip-label-bottom">
                      
                      <CustomInput
            type="radio"
            id="exCustomRadio"
            name="internType"
            value="1"
            onChange={this.onSiteChanged}
            label="Staj Yeri Bulmak İçin İlan Veriyorum"
          />             
           
          <CustomInput
            type="radio"
            id="exCustomRadio2"
            name="internType"
            value="0"
            onChange={this.onSiteChanged}
            label="Stajyer Öğrenci Bulmak İçin İlan Veriyorum"
          />   
          <br></br>
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        İlan Başlığı
                      </Label>
                      <Field
                        className="form-control"
                        name="internTitle"
                        validate={this.validateInternTitle}
                      />
                      {errors.internTitle && touched.internTitle && (
                        <div className="invalid-feedback d-block">
                          {errors.internTitle}
                        </div>
                      )}
                    </FormGroup>
                  {internType == "0" ? 
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Şirketinizin İsmi
                      </Label>
                      <Field
                        className="form-control"
                        name="internCompany"
                        validate={this.validateInternCompany}
                      />
                      {errors.internCompany && touched.internCompany && (
                        <div className="invalid-feedback d-block">
                          {errors.internCompany}
                        </div>
                      )}
                      Şirket Sektörünüz
                        <Select
            components={{ Input: CustomSelectInput }}
            className="react-select"
            classNamePrefix="react-select"
            name="workplaceSector"
            value={this.state.workplaceSector}
            onChange={this.handleChangeworkplaceSector}
            options={this.state.selectDataworkplaceSector}
          />
                    </FormGroup>
                    
                    : null }
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        İlan İçeriğiniz
                      </Label>
                    <ReactQuill
                  theme="snow"
                  value={this.state.internContent}
                  onChange={this.handleChangeQuillStandart}
                  modules={quillModules}
                  formats={quillFormats}
                  />
                    </FormGroup>
                    
                      Şehir Seçiniz
                        <Select
            components={{ Input: CustomSelectInput }}
            className="react-select"
            classNamePrefix="react-select"
            name="internCity"
            value={this.state.internCity}
            onChange={this.handleChangeCity}
            options={this.state.selectDataCity}
          />
          <br></br>
                  Pozisyon Seçiniz
                        <Select
            components={{ Input: CustomSelectInput }}
            className="react-select"
            classNamePrefix="react-select"
            name="internPosition"
            value={this.state.internPosition}
            onChange={this.handleChangePosition}
            options={this.state.selectDataPosition}
          />
                    
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
                        <span className="label">İlan Ekle</span>
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
