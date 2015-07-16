doss_class=$1/
doss_arch=$1/archives/$2/
fich_zip=$1/archives/$2.zip

mkdir -p $doss_arch
cp $doss_class*.txt $doss_arch
zip -r $fich_zip $doss_arch
