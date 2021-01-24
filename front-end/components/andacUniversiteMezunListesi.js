import React from "react";
import {
  Row,
  Card,
  CardBody,
  CardSubtitle,
  CardImg,
  CardText,
  CustomInput,
  Badge,
  Button
} from "reactstrap";
import { NavLink } from "react-router-dom";
import classnames from "classnames";
import { ContextMenuTrigger } from "react-contextmenu";
import { Colxx } from "../../components/common/CustomBootstrap";
import 'moment/locale/tr' //For Turkey
import "react-quill/dist/quill.snow.css";


const AdminImageListView = ({universitySlug, product, isSelect, collect, onCheckItem }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="3" className="mb-3" key={product.username}>
      
      <ContextMenuTrigger id="menu_id" data={product.username} collect={collect}>
      <NavLink to={universitySlug+"/@"+product.username}>
        <Card
          onClick={event => onCheckItem(event, product.username)}
          className={classnames({
            active: isSelect
          })}
        >
         
          
          
          <CardBody>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
            <CardSubtitle>
            {product.name} {product.surname}
          
            </CardSubtitle>
            <CardSubtitle>
            @{product.username}
            </CardSubtitle>
             
                <Button  outline color="secondary" className="mb-2 m-1">
                  Andaç Yaz
                </Button>
               
              </Colxx>
            </Row>
          </CardBody>
        </Card>
        </NavLink>
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(AdminImageListView);
