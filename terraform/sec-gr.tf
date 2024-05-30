resource "aws_security_group" "web-server" {
    name   = "WebServerSecurityGroup-p"
    vpc_id = data.aws_vpc.selected.id
    tags   = {
        Name = "TF_WebServerSecurityGroup"
    }

    ingress {
        from_port   = 80
        protocol    = "tcp"
        to_port     = 80
        cidr_blocks = ["0.0.0.0/0"]
    }

    ingress {
        from_port   = 22
        protocol    = "tcp"
        to_port     = 22
        cidr_blocks = ["0.0.0.0/0"]
    }

    egress {
        from_port   = 0
        protocol    = "-1"
        to_port     = 0
        cidr_blocks = ["0.0.0.0/0"]
    }
}
