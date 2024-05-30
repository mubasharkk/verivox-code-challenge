data "aws_vpc" "selected" {
    default = true
}

data "aws_ami" "al2023" {
    most_recent = true
    owners      = ["amazon"]

    filter {
        name   = "virtualization-type"
        values = ["hvm"]
    }

    filter {
        name   = "architecture"
        values = ["x86_64"]
    }

    filter {
        name   = "name"
        values = ["al2023-ami-2023*"]
    }
}


resource "aws_instance" "web" {
    ami           = data.aws_ami.al2023.id
    instance_type = "t3.micro"

    root_block_device {
        volume_size = 8
    }
    vpc_security_group_ids = [aws_security_group.web-server.id]

    tags = {
        project = "Challengers"
        Name    = "Code Challenge API Server"
    }

    key_name                = var.key-name
    monitoring              = true
    disable_api_termination = false
    ebs_optimized           = true
    user_data               = base64encode(templatefile("user-data.sh", {
        user-data-git-repo = var.git-repo
    }))
}

data "aws_route53_zone" "selected" {
    name = var.hosted-zone
}

resource "aws_route53_record" "challengers" {
    zone_id = data.aws_route53_zone.selected.zone_id
    name    = "challenge.${var.hosted-zone}"
    type    = "A"
    ttl     = 300
    records = [aws_instance.web.public_ip]
}
