import React, { Component, Fragment } from 'react';
import { Route,NavLink } from 'react-router-dom';
import { injectIntl } from 'react-intl';
import { Row, Card, CardBody,CardTitle,CardImg,CardImgOverlay,
    CardText } from 'reactstrap';
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey
import "dropzone/dist/min/dropzone.min.css";
import SEO from "./seo";
import responsiveHOC from 'react-lines-ellipsis/lib/responsiveHOC';
import LinesEllipsis from 'react-lines-ellipsis';
const ResponsiveEllipsis = responsiveHOC()(LinesEllipsis)
var ReactDOMServer = require('react-dom/server');

const anasayfaBlogData = [
    {
        id: "1",
        bigTitle: "Kariyer Hedeflerini Belirle",
        link:"/",
        badgeTitle:"Kariyer",
        smallTitle: "Kariyerin hakkında en iyi kararı vermen için tüm içerikler ve uygulamalar YbsKariyer ile yanında.",

        image: "/assets/img/kariyer-hedeflerini-belirle.jpg"
    },
    {
        id: "2",
        bigTitle: "Takım Arkadaşları Bul",
        link:"/",
        badgeTitle:"İş Fırsatları",
        smallTitle: "İş ilanı ve staj uygulamaları ile kendine uygun bir iş bulabilirsin ya da bir takım arkadaşı arayabilirsin.",

        image: "/assets/img/takim-arkadaslari-bul.jpg"
    },
    {
        id: "3",
        bigTitle: "YBS Hakkında Merak Ettiğin Her Şey",
        link:"/",
        badgeTitle:"YBS Nedir ?",
        smallTitle: "Yönetim Bilişim Sistemleri bölümü hakkında tüm bilgileri sizler için topladık.",

        image: "/assets/img/ybs-hakkinda-merak-ettigin-hersey.jpg"
    }
];

const BasicCarouselItemBlog = ({ user, blogTitle, blogContent, slug,imageHome }) => {
    return (
        <a href={"blog/"+slug}>
            <div className="glide-item" >
                <Card className="flex-row" style={{height:170}}>
                    <div className="w-50 position-relative">
                        <img className="card-img-left" src={apiUrl+"/blog/"+imageHome} alt={blogTitle} />
                    </div>
                    <div className="w-50">
                        <CardBody >
                            <h6 className="mb-4">{blogTitle}</h6>
                            <footer>
                                <ResponsiveEllipsis
                                    className="listing-desc text-muted"
                                    text={blogContent.slice(0,500)}
                                    maxLine='3'
                                    trimRight={true}
                                    basedOn='words'
                                    component="p" />
                            </footer>
                        </CardBody>
                    </div>
                </Card>
            </div>
        </a>
    );
};
const BasicCarouselItem = ({ slug, questionTitle, createdAt, user }) => {
    return (
        <a href={`/soru/${slug}`}>
            <div  className="glide-item">
                <Card className="flex-row" style={{height:170}}>

                    <div className="w-100">
                        <CardBody >
                            <h6 className="mb-4">{questionTitle}</h6>
                            <footer>
                                <p className="text-muted text-small mb-0 font-weight-light">
                                    <a href={"/profil/@"+user.username}> @{user.username}</a>
                                    <br></br><br></br>
                                    {Moment(createdAt.date).format('LL HH:mm:ss')}
                                    <br></br>
                                    {
                                        Moment(createdAt.date).startOf('hour').fromNow()
                                    }
                                </p>
                            </footer>
                        </CardBody>
                    </div>
                </Card>
            </div>
        </a>
    );
};


class Anasayfa extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoading: null,
            soru: [],
            test: [],
            selectedFile: [],
            blogLast: []

        };


    }


        var client = require('../../client');
        client.get("list-question")
            .then(
                res => {
                    if(res.data.data)
                    {
                        this.setState({soru: res.data.data})
                    }

                    this.setState({isLoading: true});
                },
                err => {

                    this.setState({isLoading: true});

                }
            )
        client.get('list-blog-last')
            .then(
                res => {
                    if(res.data.data)
                    {
                        this.setState({blogLast: res.data.data})
                    }


                },
                err => {

                }
            )
    }

    render() {
        const { soru ,test,blogLast } = this.state;

        console.log('blogda',blogLast);

        const  dropzoneComponentConfig ={
            postUrl: "http://192.168.62.1:8000/api/test-file",
            iconFiletypes: ['.jpg', '.png'],
            showFiletypeIcon: true
        };
        const   eventHandlers = { addedfile: (file) =>  {

                const reader = new FileReader();

                reader.onload = (event) => {
                    var client = require('../../client');
                    const options = {

                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                    const dataURLtoFile = (dataurl, filename) => {
                        const arr = dataurl.split(',')
                        const mime = arr[0].match(/:(.*?);/)[1]
                        const bstr = atob(arr[1])
                        let n = bstr.length
                        const u8arr = new Uint8Array(n)
                        while (n) {
                            u8arr[n - 1] = bstr.charCodeAt(n - 1)
                            n -= 1 // to make eslint happy
                        }
                        return new File([u8arr], filename, { type: mime })
                    }
                    this.setState({test: event.target.result});
                    // generate file from base64 string
                    const file = dataURLtoFile(event.target.result)
                    var bodyFormData = new FormData();
                    bodyFormData.append('file', file);
                    client.post('test-file',bodyFormData, options)
                        .then(
                            res => {

                            },
                            err => {
                                alert(err);
                            }
                        )
                    //console.log(event.target.result);
                };
                reader.readAsDataURL(file);

            } }

        const dropzoneConfig =  {

            removedfile: function(file) {
                file.previewElement.remove();

            },

            autoProcessQueue: false,
            thumbnailHeight: 160,
            maxFilesize: 2,
            previewTemplate: ReactDOMServer.renderToStaticMarkup(
                <div className="dz-preview dz-file-preview mb-3">
                    <div className="d-flex flex-row ">
                        <div className="p-0 w-30 position-relative">
                            <div className="dz-error-mark">
                <span>
                  <i />{" "}
                </span>
                            </div>
                            <div className="dz-success-mark">
                <span>
                  <i />
                </span>
                            </div>
                            <div className="preview-container">
                                {/*  eslint-disable-next-line jsx-a11y/alt-text */}
                                <img data-dz-thumbnail className="img-thumbnail border-0" />
                                <i className="simple-icon-doc preview-icon" />
                            </div>
                        </div>
                        <div className="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative">
                            <div>
                                {" "}
                                <span data-dz-name />{" "}
                            </div>
                            <div className="text-primary text-extra-small" data-dz-size />
                            <div className="dz-progress">
                                <span className="dz-upload" data-dz-uploadprogress />
                            </div>
                            <div className="dz-error-message">
                                <span data-dz-errormessage />
                            </div>
                        </div>
                    </div>
                    <a href="#/"  className="remove" data-dz-remove>
                        {" "}
                        <i className="glyph-icon simple-icon-trash" />{" "}
                    </a>
                </div>
            ),
            headers: {"Origin": "http://localhost:3000", "Access-Control-Allow-Origin": "http://localhost:3000", "Access-Control-Expose-Headers": "link",   "Authorization": "BEARER "+localStorage.getItem('token') }
        };
        return !this.state.isLoading ? (
            <div className="loading" />
        ) : (

            <Fragment>
                <SEO
                    title="Yönetim Bilişim Sistemleri Kariyer Uygulaması"
                    description="Yönetim Bilişim Sistemleri (YBS) Kariyer Uygulaması, YbsKariyer.com"
                />
                <Row>
                    <Colxx xxs="12">
                        <Breadcrumb heading="Yönetim Bilişim Sistemleri Kariyer Uygulaması" match={this.props.match} />
                        <Separator className="mb-1" />
                    </Colxx>
                </Row>
                <Row>
                    {anasayfaBlogData && anasayfaBlogData.length > 1 && anasayfaBlogData.map(item => {
                        return (

                            <ImageOverlayCard {...item}>
                            </ImageOverlayCard>



                        );
                    })}
                </Row>
                {/* <Row>

          <div className="col-4">
        <GradientCardContainer/>
          <div className="w-120 position-relative">
           <NavLink to={"/blog"}>
            <img className="card-img" src="https://gcdn.bionluk.com/uploads/story/8dd76695bd4cf74080b80f6f38cf56ae.png" alt="deneme test" />
           </NavLink>
          </div>
          </div>
          <div className="col-4">
        <GradientCardContainer/>
          </div>
          <div className="col-4">
        <GradientCardContainer/>
          </div>
        </Row> */}

                <Row>

                    <Colxx xxs="12" xs="12" lg="6">

                        <Card>
                            {localStorage.getItem('__theme_color') && localStorage.getItem('__theme_color').includes("dark") ?

                                <img
                                    style={{borderRadius:10}}
                                    src="/assets/img/banner-black-univeriste-ogrenci-mezun-bilgi-sistemi-ybskariyer-com.jpg"
                                    alt="Card image cap"
                                />

                                :

                                <img
                                    style={{borderRadius:10}}
                                    src="/assets/img/banner-univeriste-ogrenci-mezun-bilgi-sistemi-ybskariyer-com.jpg"
                                    alt="Card image cap"
                                />

                            }


                        </Card>

                    </Colxx>

                    <Colxx xxs="12" xs="12" lg="6">

                        <Card>

                            {localStorage.getItem('__theme_color') && localStorage.getItem('__theme_color').includes("dark") ?

                                <img
                                    style={{borderRadius:10}}
                                    src="/assets/img/banner-black-staj-ve-is-ilani-ybskariyer-com.jpg"
                                    alt="Card image cap"
                                />

                                :

                                <img
                                    style={{borderRadius:10}}
                                    src="/assets/img/banner-staj-ve-is-ilani-ybskariyer-com.jpg"
                                    alt="Card image cap"
                                />

                            }


                        </Card>

                    </Colxx>
                </Row>

                <Row>


                    {soru.length ?
                        <Colxx  xxs="12" xs="12" lg="6" className="pl-0 pr-0 mb-5">
                            <Colxx xxs="12">
                                <div className="text-center pt-4">
                                    <h5 className="list-item-heading pt-2">Son Sorulan Sorular</h5>
                                </div>
                            </Colxx>
                            <GlideComponent settings={
                                {
                                    gap: 5,
                                    perView: 2,
                                    type: "carousel",
                                    breakpoints: {
                                        600: { perView: 1 },
                                        600: { perView: 1 }
                                    }
                                }
                            }>

                                {soru && soru.map(item => {
                                    return (
                                        <div key={item.id}>
                                            <BasicCarouselItem {...item} />
                                        </div>
                                    );
                                })}
                            </GlideComponent>
                        </Colxx>
                        : null}

                    {blogLast && blogLast.length > 0 ?
                        <Colxx  xxs="12" xs="12" lg="6" className="pl-0 pr-0 mb-5">
                            <Colxx xxs="12">
                                <div className="text-center pt-4">
                                    <h5 className="list-item-heading pt-2">Son Yazılan Bloglar</h5>
                                </div>
                            </Colxx>
                            <GlideComponent settings={
                                {
                                    gap: 5,
                                    perView: 1,
                                    type: "carousel",
                                    breakpoints: {
                                        600: { perView: 1 },
                                        600: { perView: 1 }
                                    }
                                }
                            }>
                                {blogLast && blogLast.length > 0 && blogLast.map(item => {
                                    return (
                                        <div key={item.id}>
                                            <BasicCarouselItemBlog {...item} />
                                        </div>
                                    );
                                })}
                            </GlideComponent>
                        </Colxx>
                        : null}

                </Row>

                {/* <Row>
          <Colxx xxs="6">
          <Card >

          <img className="card-img-left" src="/assets/img/bilgi-sistemi-tanitim.jpg" alt="test" />

      </Card>
          </Colxx>
          <Colxx xxs="6">
          <Card >

          <img className="card-img-left" src="/assets/img/bilgi-sistemi-tanitim.jpg" alt="test" />

      </Card>
          </Colxx>
        </Row> */}





            </Fragment>

        );
    }
}
export default injectIntl(Anasayfa);
