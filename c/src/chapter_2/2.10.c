#include <stdio.h>
#include <limits.h>
#include <float.h>

int main(void)
{
	printf("Variables of type char store values from %d to %d", CHAR_MIN, CHAR_MAX);
	printf("\nVariables of type unsigned char store values from %d to %d", 0, UCHAR_MAX);

	printf("\nVariables of type short store values from %d to %d", SHRT_MIN, SHRT_MAX);
	printf("\nVariables of type unsigned short store values from %d to %d", 0, USHRT_MAX);

	printf("\nVariables of type int store values from %d to %d", INT_MIN, INT_MAX);
	printf("\nVariables of type unsigned int store values from %d to %d", 0, UINT_MAX);

	printf("\nVariables of type long store values from %ld to %ld", LONG_MIN, LONG_MAX);
	printf("\nVariables of type unsigned long store values from %ld to %ld", 0, ULONG_MAX);

	printf("\nVariables of type long long store values from %lld to %lld", LONG_MIN, LONG_MAX);
	printf("\nVariables of type unsigned long store values from %lld to %lld", 0, ULONG_MAX);

	printf("\n\nThe size of the smallest non-zero value of type float is %.3e", FLT_MIN);
	printf("\nThe size of the largest non-zero value of type float is %.3e", FLT_MAX);

	printf("\nThe size of the smallest non-zero value of type double is %.3e", DBL_MIN);
	printf("\nThe size of the largest non-zero value of type double is %.3e", DBL_MAX);

	printf("\nThe size of the smallest non-zero value ~CCC of type long double is %.3Le", LDBL_MIN);
	printf("\nThe size of the largest non-zero value of type long double is %.3Le", LDBL_MAX);

	printf("\nVariables of type float provide %u decimal digits precision.", FLT_DIG);
	printf("\nVariables of type double provide %u decimal digits precision.", DBL_DIG);
	printf("\nVariables of type long double provide %u decimal digits precision.", LDBL_DIG);

	return 0;
}
