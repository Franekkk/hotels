# Generated by Django 2.1.3 on 2018-11-14 10:06

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('reservations', '0001_initial'),
    ]

    operations = [
        migrations.AddField(
            model_name='roomtype',
            name='capacity',
            field=models.IntegerField(default=0),
            preserve_default=False,
        ),
    ]
