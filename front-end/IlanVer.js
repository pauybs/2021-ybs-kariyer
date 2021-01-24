import React, { Component, Fragment } from 'react';
import { Route } from 'react-router-dom';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,Jumbotron,Button, FormGroup, Label ,CustomInput } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import { Formik, Form, Field } from "formik";
import { NotificationManager } from "../../components/common/react-notifications";
import Select from "react-select";
import CustomSelectInput from "../../components/common/CustomSelectInput";
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
      jobTitle: "",
      jobContent: "",
      jobCompany: "",
      jobCity: "",
      jobPosition: "",
      workplaceSector: "",
      jobType: null,
      selectDataCity: [],
      selectDataPosition: [],
      selectDataworkplaceSector: []

    };
  }

  handleChangeQuillStandart = (jobContent) => {
    this.setState({ jobContent });
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

  onJobAdCreate = (values) => {
    if(!this.state.jobType)
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
    if(!this.state.jobPosition.value)
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

      if(!this.state.jobCity.value)
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
      if(this.state.jobType == 0 && !this.state.workplaceSector.value)
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
      values.jobTitle !== "" && this.state.jobContent !== "" 
      ) {
      this.setState({loading : 1}); 
   
     var bodyFormData = new FormData();
     bodyFormData.append('jobTitle', values.jobTitle);
     bodyFormData.append('jobContent', this.state.jobContent);
     bodyFormData.append('jobCompany', values.jobCompany);
     bodyFormData.append('jobCity', this.state.jobCity.value);
     bodyFormData.append('jobPosition',  this.state.jobPosition.value);
     bodyFormData.append('jobType', this.state.jobType);
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

      client.post("add-job-ad", bodyFormData, options)
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
        values.jobTitle = "";
        values.jobCompany = "";
        values.jobCity = "";
        values.jobPosition = "";
        values.jobType = "";
        
    this.setState({jobContent: ""});
    this.setState({jobCity: ""});
    this.setState({jobPosition: ""});
    this.setState({workplaceSector: ""})
    }
    
}

  validateJobTitle = (value) => {
    let error;
    if (!value) {
      error = "Lütfen ilan başlığını yazınız!";
    } else if (value.length < 2) {
      error = "İlan başlığı 2 karakterden az olamaz !";
    }
    return error;
  }

  validateJobCompany = (value) => {
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
      jobType: e.currentTarget.value
      });
  }


  handleChangeCity = jobCity => {
    this.setState({ jobCity });
  };

  handleChangePosition = jobPosition => {
    this.setState({ jobPosition });
  };

  handleChangeworkplaceSector = workplaceSector => {
    this.setState({ workplaceSector });
  };
  render() {
    const { jobTitle,jobContent,jobCompany,jobCity,jobPosition,jobType,loading,workplaceSector } = this.state;
    const initialValues = {jobTitle,jobContent,jobCompany,jobCity,jobPosition,jobType,workplaceSector};
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
          
            <Breadcrumb heading="İş İlanı Ekle" match={this.props.match} />
            
            <br></br>
            <NavLink to="/is-ilanlari">
            <Button  outline color="secondary" className="mb-2 m-1">
                 İş İlanları
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
                onSubmit={this.onJobAdCreate}>
                {({ errors, touched }) => (
                  <Form className="av-tooltip tooltip-label-bottom">
                      
                      <CustomInput
            type="radio"
            id="exCustomRadio"
            name="jobType"
            value="1"
            onChange={this.onSiteChanged}
            label="Bir İş Bulmak İçin İlan Vereceğim"
          />             
           
          <CustomInput
            type="radio"
            id="exCustomRadio2"
            name="jobType"
            value="0"
            onChange={this.onSiteChanged}
            label="Çalışan Bulmak İçin İlan Vereceğim"
          />   
          <br></br>
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        İlan Başlığı
                      </Label>
                      <Field
                        className="form-control"
                        name="jobTitle"
                        validate={this.validateJobTitle}
                      />
                      {errors.jobTitle && touched.jobTitle && (
                        <div className="invalid-feedback d-block">
                          {errors.jobTitle}
                        </div>
                      )}
                    </FormGroup>
                  {jobType == "0" ? 
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Şirketinizin İsmi
                      </Label>
                      <Field
                        className="form-control"
                        name="jobCompany"
                        validate={this.validateJobCompany}
                      />
                      {errors.jobCompany && touched.jobCompany && (
                        <div className="invalid-feedback d-block">
                          {errors.jobCompany}
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
                  value={this.state.jobContent}
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
            name="jobCity"
            value={this.state.jobCity}
            onChange={this.handleChangeCity}
            options={this.state.selectDataCity}
          />
          <br></br>
                  Pozisyon Seçiniz
                        <Select
            components={{ Input: CustomSelectInput }}
            className="react-select"
            classNamePrefix="react-select"
            name="jobPosition"
            value={this.state.jobPosition}
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
